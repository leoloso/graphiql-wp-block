# GraphiQL WordPress block

WordPress block for adding a GraphiQL client, to query the GraphQL server

## Usage

Include in your project through Composer:

``` bash
$ composer require leoloso/graphiql-wp-block dev-master
```

**Note:** Your `composer.json` file must have the configuration below to accept minimum stability `"dev"` (there are no releases for PoP yet, and the code is installed directly from the `master` branch):

```javascript
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```

Then, initialize by running:

```php
(new \Leoloso\GraphiQLWPBlock\Block())->init();
```

## Credits

- [Leonardo Losoviz][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/leoloso
