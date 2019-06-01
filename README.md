# Buto-Plugin-ButoHtml_to_yml

This is a page plugin to parse HTML to YML in Buto element format. It´s for development purpose in Buto.
Insert HTML in a textarea and the YML result appears in another textarea.

## Settings

```
plugin_modules:
  html_to_yml:
    plugin: 'buto/html_to_yml'
```

## Url

```
/html_to_yml/form
```

## Example

HTML to parse to YML.

```
<table id="my_table">
<tr>
<th>Name</th>
<td>John Smith</td>
</tr>
</table>
```

YML result.

```
-
  type: table
  attribute:
    id: my_table
  innerHTML:
    -
      type: tr
      innerHTML:
        -
          type: th
          innerHTML: Name
        -
          type: td
          innerHTML: 'John Smith'
```

## Issues

### End tag

All elements must have end tags. Even if they don´t need it for HTML dom purpose they have to exist in this parse process.

```
<br> -> <br/>
<link> -> <link></link>
<img src=""> -> <img src=""/>
```

### Mixed tags with text

Mix tags with text will end up in incorrect result.

```
<div>Hello <strong>World</strong></div>
```

Result.

```
-
  type: div
  innerHTML: 'Hello '
```

Do like this.

```
<div><span>Hello</span><strong>World</strong></div>
```

Result.

```
-
  type: div
  innerHTML:
    -
      type: span
      innerHTML: Hello
    -
      type: strong
      innerHTML: World
```
