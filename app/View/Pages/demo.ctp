<?php
/**
 * CSS Demo Page
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Pages.demo
 * @since         version 2.12.6
 */
?>
<div class="sidebar left">
<?php echo $this->element('tree'); ?>
</div>
<div class="content right">
<?php //echo $this->element('actions'); ?>
<?php echo $this->element('breadcrumb'); ?>
<table>
<tr>
  <th class="small">
    <div class="input checkbox">
      <input type="checkbox" name="data[Passwords][5055a223-6dfc-4687-0000-0dc003963937]" value="SelectAll" id="PasswordsSelectAll" />
      <label class="no_text"><span>All</span></label>
    </div>
  </th>
  <th class="small">
    <a href="#" class="with_icon no_text fav"><span>Fav</span></a>
  </th>
  <th>
    <a href="#" class="sort asc">Title</a>
  </th>
  <th>
    <a href="#" class="sort dsc">Username</a>
  </th>
  <th>
    <a href="#">Password</a>
  </th>
</tr>
<tr>
  <td>
    <div class="input checkbox">
      <input type="checkbox" name="data[Passwords][5055a223-6dfc-4687-0000-0dc003963937]" value="SelectAll" id="PasswordsSelectAll" />
      <label class="no_text"><span>select</span></label>
    </div>
  </td>
  <td>
    <a href="#" class="with_icon no_text unfav"><span>Unfav</span></a>
  </td>
  <td>
    <a href="#">Title</a>
  </td>
  <td>
    <a href="#">Username</a>
  </td>
  <td>
    <a href="#">Password</a>
  </th>
</tr>
</table>
</div>
</div>
