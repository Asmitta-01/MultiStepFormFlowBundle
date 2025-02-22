# MultiStepFormFlowBundle

This is a fork of the package [craue/CraueFormFlowBundle](https://github.com/craue/CraueFormFlowBundle) version __3.7.0__. You can check it to know all the features of this package.
In this fork i focus on the usage and installation on Symfony 7.

> :warning: __Note__: This fork doesn't support a Symfony version prior to 5.3.

## Installation

### Get the bundle

Let Composer download and install the bundle by running the command

```sh
composer require asmitta-01/formflow-bundle
```

### Enable the bundle

If you don't use Symfony Flex, register the bundle manually:

```php
// in config/bundles.php
return [
 // ...
  Asmitta\FormFlowBundle\AsmittaFormFlowBundle::class => ['all' => true],
];
```

## Usage

This section shows how to create a 3-step form flow for a user. The package provides 03 approaches but i will focus on one: One form type for the entire flow.
This approach makes it easy to turn an existing (common) form into a form flow.
We will use this _FormType_:

```php
// File: src/Form/UserType.php
<?php

// Imports...

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('first_name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter your first name'
                ],
            ])
            ->add('last_name')
            ->add('phone_number')
            ->add('professional_qualification')
            ->add('gender', EnumType::class, [
                'class' => Gender::class,
                'mapped' => false,
                'expanded' => true,
                'label' => 'gender',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

```

### 1. Create a flow class

```php
// src/Form/UserFlow.php
<?php

namespace App\Form;

use Asmitta\FormFlowBundle\Form\FormFlow;

class UserFlow extends FormFlow
{

    protected function loadStepsConfig(): array
    {
        return [
            [
                'label' => 'Name',
                'form_type' => UserType::class,
            ],
            [
                'label' => 'Contact',
                'form_type' => UserType::class,
            ],
            [
                'label' => 'Profession',
                'form_type' => UserType::class,
            ],
        ];
    }
}
```

### 2. Update the form type class

There is an option called `flow_step` you can use to decide which fields will be added to the form
according to the step to render.

```php
// UserType class
...
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        switch ($options['flow_step']) {
            case 1:
                $builder
                    ->add('first_name', TextType::class, [
                        'attr' => [
                            'placeholder' => 'Enter your first name'
                        ],
                    ])
                    ->add('last_name');
                break;
            case 2:
                $builder
                    ->add('email')
                    ->add('phone_number');
                break;
            case 3:
                $builder
                    ->add('professional_qualification')
                    ->add('gender', EnumType::class, [
                        'class' => Gender::class,
                        'mapped' => false,
                        'expanded' => true,
                        'label' => 'gender',
                    ]);
                break;
        }
    }

```

### 3. Register your flow as a service

```yaml
# config/services.yaml
services:
    App\Form\userFlow:
        parent: asmitta.form.flow
```

### 4. Setup your controller

```php
// in src/Controller/SomeController.php
final class SomeController extends AbstractController
{
    private $flow;

    public function __construct(UserFlow $flow)
    {
        $this->flow = $flow;
    }
 
    public function someMethod(): Response
    {
        $user = new User();

        // Get existing flow data from session if it exists
        $this->flow->bind($user);
        $form = $this->flow->createForm();

        if ($this->flow->isValid($form)) {
            $this->flow->saveCurrentStepData($form); // Save data in session
            $user = $form->getData();

            if ($this->flow->nextStep()) {
                $form = $this->flow->createForm(); // Go to the next step
            } else {
                // Persist data here
                $this->flow->reset(); // remove all data from the session
                return $this->redirectToRoute('some_route'); // redirect when done
            }
        }

        return $this->render(
            'custom_template.html.twig',
            [
                'form' => $form->createView(),
                'flow' => $this->flow,
            ],
        );
    }
}
```

### 4. Create a form template(view)

You only need one template for a flow.
The instance of your flow class is passed to the template in a variable called `flow` so you can use it to render the
form according to the current step.

```twig
{# in src/templates/custom_template.html.twig #}
<div>
  {{ form_start(form, {attr: {class: 'needs-validation', novalidate: ''}}) }}
  {{ form_errors(form) }}

  {{ form_rest(form) }}
  <div class="mt-5">
    {% include '@AsmittaFormFlow/FormFlow/buttons.html.twig' %}
  </div>
  {{ form_end(form) }}
</div>
```

## Buttons

You can customize the default button look by using these variables to add one or more CSS classes to them:

- `asmitta_formflow_button_class_last` will apply either to the __next__ or __finish__ button
- `asmitta_formflow_button_class_finish` will specifically apply to the __finish__ button
- `asmitta_formflow_button_class_next` will specifically apply to the __next__ button
- `asmitta_formflow_button_class_back` will apply to the __back__ button
- `asmitta_formflow_button_class_reset` will apply to the __reset__ button

Example with Bootstrap button classes:

```twig
{% include '@AsmittaFormFlow/FormFlow/buttons.html.twig' with {
  asmitta_formflow_button_class_last: 'btn btn-primary',
  asmitta_formflow_button_class_back: 'btn',
  asmitta_formflow_button_class_reset: 'btn btn-warning',
 } %}
```

In the same way you can customize the button labels:

- `asmitta_formflow_button_label_last` for either the __next__ or __finish__ button
- `asmitta_formflow_button_label_finish` for the __finish__ button
- `asmitta_formflow_button_label_next` for the __next__ button
- `asmitta_formflow_button_label_back` for the __back__ button
- `asmitta_formflow_button_label_reset` for the __reset__ button

Example:

```twig
{% include '@CraueFormFlow/FormFlow/buttons.html.twig' with {
  asmitta_formflow_button_label_finish: 'Submit',
  asmitta_formflow_button_label_reset: 'Reset the flow',
 } %}
```

You can also remove the reset button by setting `asmitta_formflow_button_render_reset` to `false`.

The buttons are displayed in a `div` with the class _asmitta_formflow_buttons_. You can override its rules in your CSS file.

```css
/* Default*/
.asmitta_formflow_buttons {
   overflow: hidden;
}
```

## Handle Unmapped Fields

If you have unmapped fields in your form, you can handle them at the end of the form fill.
In our [previous example](#usage), assuming the `first_name` is unmapped we'll need to handle it ourself.

```php
// In our controller
...
        if ($this->flow->isValid($form)) {
            $this->flow->saveCurrentStepData($form);

            if ($this->flow->nextStep()) {
                $form = $this->flow->createForm();
            } else {
                $stepNumber = 1; // It is the step where 'first_name' field is displayed
                $user->setFirstName($this->flow->getStepData($stepNumber)['first_name']);

                // Persist data here
                $this->flow->reset(); 
                return $this->redirectToRoute('some_route'); // redirect when done
            }
        }
...
```

## Symfony UX Turbo

Working with [Symfony Turbo](https://ux.symfony.com/turbo) the flow in the view might not work correctly. You should __disable it__ on that view or __send a specific code with the controller__.

- Disabling Turbo: o disable Turbo on a specific page in Symfony, you can use the `data-turbo` attribute. For example:

```html
<div data-turbo="false">
    <!-- Content that should not use Turbo -->
    <!-- Insert your form here -->
</div>
```

- Sending a http code with the controller:

```php
// Your Controller method
        return $this->render(
            'custom_template.html.twig',
            [
                'form' => $form->createView(),
                'flow' => $this->flow,
            ],
            new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY) 
            // Without this Symfony Turbo will not load the new steps, it will stuck on the first step.
        );
```
