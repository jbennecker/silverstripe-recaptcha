# Recaptcha FormField Module

## Introduction

This module adds a RecaptchaField to SilverStripe 4.x, which you can use in custom forms.

![](https://raw.githubusercontent.com/jbennecker/silverstripe-recaptcha/master/screenshot.png)


## Usage

Put your keys in app/_config/app.yml

```yml
jbennecker\recaptcha\Forms\RecaptchaField:
  public_api_key: "public key"
  private_api_key: "private key"
```

Then you can use the Field in your forms.

```php
    public function HelloForm()
    {
        $fields = new FieldList(
            TextField::create('Name', _t('HelloForm.Name', 'Name')),
            TextField::create('Email', _t('HelloForm.Email', 'E-Mail')),
            TextareaField::create('Nachricht', _t('HelloForm.Nachricht', 'Nachricht')),
            RecaptchaField::create('recaptcha') // <--- add this
        );

        $actions = new FieldList(
            FormAction::create('doSayHello')->setTitle(_t('HelloForm.Submit', 'Senden'))
        );

        $required = new RequiredFields('Name', 'Email', 'Nachricht');

        $form = new Form($this, 'HelloForm', $fields, $actions, $required);

        return $form;
    }
```
