# 

[![Test Suite Status](https://github.com/lphilippo/laravel-castable-form-request/workflows/Test%20Suite/badge.svg)](https://github.com/lphilippo/laravel-castable-form-request)
[![Total Downloads](https://poser.pugx.org/lphilippo/laravel-castable-form-request/d/total.svg)](https://packagist.org/packages/lphilippo/laravel-castable-form-request)
[![Latest Stable Version](https://poser.pugx.org/lphilippo/laravel-castable-form-request/v/stable.svg)](https://packagist.org/packages/lphilippo/laravel-castable-form-request)
[![License](https://poser.pugx.org/lphilippo/laravel-castable-form-request/license.svg)](https://github.com/lphilippo/laravel-castable-form-request)

First of all, you can now use Laravel-like form-requests within Lumen. Additionally you can extend your FormRequests with default values and (nested!) casting, to have full control over the data that enters your application. It's important to note that also for nested array, validation rules are required for each property, as otherwise the property will be omitted from the sanitised result.

The `defaults()` are combined with the data source of the request prior to validation, whereas the `casts()` are being applied once validation is succesfull. By enforcing this order, the casts can be used to control in which type the data is provided to the application, regardless of the validation of the user data. This is especially usefull for working with `Carbon` dates internally.

There is no distinction made between the various input sources of the request, similar to the standard implemenation of FormRequest in Laravel [Illuminate/Foundation/Http/FormRequest](https://laravel.com/api/7.x/Illuminate/Foundation/Http/FormRequest.html).


## Installation

```bash
composer require lphilippo/laravel-castable-form-request
```


## Configuration for Lumen

To use the form-requests in Lumen, you will only need to register the service provider in `bootstrap/app.php`.

```php
$app->register(LPhilippo\CastableFormRequest\ServiceProvider::class);
```

## Usage in Lumen

Simply extend your own form-request from `LPhilippo\CastableFormRequest\Http\Requests\LumenFormRequest` and add the rules, casts and defaults that you need.

## Usage in Laravel

As the `FormRequest` of this package should not be used over the standard one of Laravel (unless you have no need for the redirect actions), all functionality can still be achieved with a bit more code.

The `FormRequest` simply extends the base class and adds the `LPhilippo\CastableFormRequest\Casting\CastsWhenValidatedTrait`. This will allow the `casts()` to be applied, but in order to make use of the `defaults()` as well, please add the following code to your class:

```php
    /**
     * Allow default values through filtering before validation.
     *
     * @return array
     */
    public function prepareForValidation()
    {
        return $this->replace(
            array_merge(
                $this->defaults(),
                $this->all()
            )
        );
    }
```

## Full FormRequest example:

A full example that uses the validation, the casts and the default values within the same implementation, would be:

```php
use LPhilippo\CastableFormRequest\Http\Requests\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
           'id' => 1,
       ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           'created_at' => 'required|date_format:Y-m-d',
           'id' => 'required|integer',
       ];
    }
   
    /**
     * @inheritdoc
     */
    public function casts()
    {
        return [
           'created_at' => 'datetime',
           'id' => 'integer',
       ];
    }
}
```

In your controller you can then access either the validated uncasted data:

```php
$request->validated();
```

Or the fully sanitised data, which is prepared for further internal processing.

```php
$request->sanitised();
```

Both the `validated` as the `sanitised` methods do not include properties that are not validated. This also applies for nested arrays, which will be stripped from properties without validation.

## Nested validation rules

Similar to standard validation rules, also casting rules can be nested. For example, the following rules are valid. Please keep in mind that casting rules should always work alongside validation rules (which are applied first), as otherwise the outcome of the casts could be unexpected.

```php
    /**
     * @inheritdoc
     */
    public function casts()
    {
        return [
            'products' => 'array',
            'products.*' => 'array',
            'products.*.id' => 'int',
            'products.*.category_id' => 'array',
            'products.*.category_id.*' => 'int',
    }
```

Although this can be written shorter by only defining the dot notation, as the array rules will be implied already:


```php
    /**
     * @inheritdoc
     */
    public function casts()
    {
        return [
            'products.*.id' => 'int',
            'products.*.category_id.*' => 'int',
    }
```


## Adding custom validator rules

In certain use cases, specifying all validation rules in the `$rules` variable is not enough. For example, if based on the request route, more tailored validation is needed, this can be achieved with adding something along the following structure:

```php
    /**
     * @inheritdoc
     */
    public function withValidator($validator)
    {
        $entityId = $this->route('id');

        if ($entityId === 1) {
            $validator->addRules([
                'key' => 'required|string|in:' . implode(',', self::RESTRICTED_KEYS),
            ]);
        }
    }
```



