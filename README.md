# Introduction

Symbiont is a purpose-built language for expressive data manipulation. It is
named so, because it relies on a mutualistic symbiotic relationship with a
host language.

As an expressive scripting language, Symbiont is able to keep and update state
of data, as well as consume from and publish to external data sources.
The extent of these capabilities heavily rely on the host language and
implementation with that language.

By design, the language is intended to be decoupled from the host language. The
main host language is PHP. However, the syntax is purposely designed around
[Vaughan Pratt's Top Down Operator Precedence](https://dl.acm.org/citation.cfm?id=512931).
The implementation details are referenced from
Douglas Crockford's paper on a
[parser for Simplified JavaScript](http://crockford.com/javascript/tdop/)
that is written in Simplified Javascript.

## In the wild

Symbiont is used as an example language for the talk
[On top of the Elephpant](https://www.meetup.com/GroningenPHP/events/jhvhqrybcpbhb/).
*(Nov. 5th 2020)*

Feel free to join. No prior knowledge of programming language design is expected.

## Syntax example

A brief example of the syntax:

```javascript
$catalog: @products;
$product: $catalog.ensure('tnt');

$product
    .branch('media_gallery_entries.*')
    .filter(
        $image => /portrait\.(jpg|png)$/.test($image.get('file'));
    );
```

The example consists of:

- Variable `$catalog` pointing to a storage named `products`.
- Variable `$product` pointing to an entity `tnt` from storage `products`.
- Variable `$image` pointing to an image node, within the `$product` data structure.

The following operations are performed:

1. Variable `$catalog` points to storage `products`.
2. Variable `$product` points to storage `products` entry `tnt`.
   If entry `tnt` could not be found, it is created according to a predetermined
   structure, specific to the `products` storage.
3. For each child of the `media_gallery_entries` data node of `$product`, a filter
   is applied. Only entries with a `file` that ends in `portrait.jpg` or
   `portrait.png` is kept.
4. End of program clean-up is triggered. This ensures the modifications on
   `$product` are persisted to the `products` storage.

The previous example is written verbosely to highlight individual parts of the
syntax. The following code should work exactly the same:

```javascript
@products
    .ensure('tnt')
    .branch('media_gallery_entries.*')
    .filter(
        $image => /portrait\.(jpg|png)$/.test($image.file);
    );
```
