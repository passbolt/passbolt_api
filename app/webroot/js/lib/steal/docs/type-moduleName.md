@typedef {String} moduleName
@parent StealJS.types

@option {String}

A Loader-unique string that represents a module.

@body

It's important to understand the difference between a moduleName and module identifier. For example:

<table>
<thead>
<tr>
	<th>Module identifier</th><th>Module name</th>
</tr>
</thead>
<tbody>
<tr><td>./dep</td><td>app/util/dep</td></tr>
<tr><td>styles.css!</td><td>styles.css!$css</td></tr>

</tbody>
</table>
