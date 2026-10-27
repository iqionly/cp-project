## Subresource Integrity

If you are loading Highlight.js via CDN you may wish to use [Subresource Integrity](https://developer.mozilla.org/en-US/docs/Web/Security/Subresource_Integrity) to guarantee that you are using a legimitate build of the library.

To do this you simply need to add the `integrity` attribute for each JavaScript file you download via CDN. These digests are used by the browser to confirm the files downloaded have not been modified.

```html
<script
  src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"
  integrity="sha384-KnPvYPx1poT554tHDV1nuYV9sOkh4cZPBvLZQlXgJmoRQZPdgQNwL50/xq9kynp9"></script>
<!-- including any other grammars you might need to load -->
<script
  src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/languages/go.min.js"
  integrity="sha384-orYKHAs3chK3oDMQLy5ywrzoY8z9zvzfmNIjmVxKXioAUtwDhP+xf6THWYSI/43Y"></script>
```

The full list of digests for every file can be found below.

### Digests

```
sha384-amdMjFrQeV1IlGyVyYRGeUBxPp1NVz7WG5xs0heAwCiAZLj0ISxeJwiOTeom9RfS /es/languages/css.js
sha384-rLeEizUP6J+98gF7EZ4ngav3h+slU5SqCVDahqOoYBEdjzhWQ3g6XldnqR9BSlBR /es/languages/css.min.js
sha384-o4UvWNmnl1L+ChjYSiEzv6+SmutMhWyMN6YBulDKvTe8IUDetXNVFtu3jjjUcEy6 /es/languages/ini.js
sha384-om1+Rx6vks+orizrPIf1OpBFEOqHVfjPlx6hSoIY80V1fDLkPa69GFlafLtsMSWQ /es/languages/ini.min.js
sha384-lt0gg86v1uEAI7/c2402dN+9T2iQXopS6OJ8P1jHo1uOZu1zIV5sX8vmlOLGozq0 /es/languages/json.js
sha384-UJwfLbfKiYs+crNOV1xJL6wDet7JiH/Kav6qZ9c+cOjar4piP1X0VQqwVE3RJt+z /es/languages/json.min.js
sha384-47Dn27oZJHoNVUL4leYvOLfxpXtmetYh9lZ7olKoDqzPebdZqqLE3xNY9iHEu3O/ /es/languages/lua.js
sha384-RUO5JMeXmbOe7bDSAF/Yzw4V9xXupHGlmNKK4+/UZIPdock3BGdBwaWYzM+j5se2 /es/languages/lua.min.js
sha384-a7CJHRB29/7Hhj+fm2T+rVAfyTTsDBmFA7Jyb8gWyA/IX71UDkK0TBanG+K4T75i /es/languages/php.js
sha384-HmKbGhJX2Y98LBeAFLqI79rljxMw9FbDEbbWQfYo3vTeMjCLncFCwSrdgegOv9go /es/languages/php.min.js
sha384-M29KVshhb1nOr+V9fD7zculEkusgFmZdZpLoQxEiEJ7nTncEiXzJ9/tXuofiOIYK /es/languages/python.js
sha384-1SN2ySEZrgpb2XQB4PRJli/u1Jj5sm1j+Be77jnSNNmKzhUZK8OBkGFFexuOPSHu /es/languages/python.min.js
sha384-B2tWwx3GmC07EO8LzXOdP8ST25VZHxugHG+XTI4uPSvRGWHnTPKEWip5WweN7GnW /es/languages/shell.js
sha384-UO9hFSZLgfmMtuKIFmLhOz4OMlYKPDnZyxHVmn47vrmiTA6MqC2D/Y5rdVO49Alf /es/languages/shell.min.js
sha384-1x+arn/A8CSZOUs83+Fa6bwOOwxzz9Fqc+OZ3YVh+yVOvF5H6uvqHDx6Bh4UNAp2 /es/languages/sql.js
sha384-e1St/oZyx5GxD71Zry3asHLIZmg/b20NgNJLUwvput4g+SZj8Rjuq+aP7pdWC2qh /es/languages/sql.min.js
sha384-+G97Y66qjmfAEeNK5AYrOqbLn/hBNX41qhtyiVW7z3Zq/1llyjGJr3gHmNi+AVKN /languages/css.js
sha384-FvHR2wIZNmDX0TgSuoOhAZRl6R5yRi26wu2/MVXDm1ZFCGJUvotj2RrvVLGC4y88 /languages/css.min.js
sha384-0tQ9l/wLu1PEfcESn4J1X2XjEGE4pKgn9SG3tfgEpRwX0yuZfCPtxSDQmjOgoO+b /languages/ini.js
sha384-FtUKo+hpWyLS/IYUX6GZGKptmt2igNPFsWSqPRz7qk7cnN+/sbt/22tz8lH2iEyk /languages/ini.min.js
sha384-xRs5pKapNPranWV1tpWwbWD8FN6u6gwlBXWwW+3wcfgCrXtc+VuTjt6Ff2MZTUoZ /languages/json.js
sha384-BuKQB2q4LIWSYzKqO0qkAQOdYLvqUmSmzUtLZDkTHy5po4tY7DSEcu/r5555QON2 /languages/json.min.js
sha384-X7kIOhO4b0cvc9Ro4AM1xtGHL0uSrOPcvdCaVRV4R2bp0WfZ+rwq0nRA+gcYmRsU /languages/lua.js
sha384-4O9XXj4HbJ3xc7QSKua6ZC/g7oB7lUP5VJo2Otc6LY62LJVfV/Ruod9z3NLR+W2R /languages/lua.min.js
sha384-KHE4nh2fKFHXTz70wPh2Q72wGbHIhBIXxhb0BhVPkWtlwAbUMFS3pSLw1c6P1THK /languages/php.js
sha384-67UUzIXiBnxtse2TXo+O7wMnXvIhxA+tqtSUpHiWsRJG3S7R+ORDGARRpw0xCcND /languages/php.min.js
sha384-vHFyigyrxXKrLcfzhSiSgjFvq0h4IaawkRiHBKD7g4xx6ny6hQvUeIe5o0vcr2Qi /languages/python.js
sha384-MjEDnJ45PjESeDQtPkC0gdCZlwA6MWEYLl/Qw95D9e1Ow8yAlz/xkS/EDIxFiig6 /languages/python.min.js
sha384-72beB65rbLzMOcE4+YWKx5ru8/Ond638ZZwF6NlcoledcqrIWooTDsjNuAzieK1u /languages/shell.js
sha384-laOtV6vhnnfvegRPIJtGS2eVsVZ71Kh6gTxOoDhXpl91W1i6NYgtojnE+PFKWm0c /languages/shell.min.js
sha384-a+Iw5odyfpVyqnZLhXgjgfrTGL83SYa8BG2NpF/DmbvRloJefgDUZEDQ4j2662cz /languages/sql.js
sha384-xmw+Wgf/U9GjB60m9/I4SBh8zETMwAFC8GqSL7kCfkfgoKHDffjcnkQrNpQLc/96 /languages/sql.min.js
sha384-HAi8LRE07f0dC//uwQc7crgxI7qaq24mu0fw7EdJhTiBTo0T/oCNUtzTeOesQorY /highlight.js
sha384-mROxX9qkBzQ6kWpLLWdFyLZxhdoPV702qQg0+fVUl6Yws1jyzlDmwkIwqDcncI2P /highlight.min.js
```

