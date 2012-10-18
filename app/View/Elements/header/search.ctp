<?php
/**
 * Search Box
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.header.search
 * @since         version 2.12.11
 */
	$this->Html->css('search.css', null, array('block' => 'css'));
?>
  <div class="search">
    <form action="#" id="PassworSearchForm" method="post" accept-charset="utf-8">
      <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
      <fieldset> 
        <legend><?php echo __('Please enter a search keyword'); ?></legend> 
        <div class="input text required">
          <label for="PasswordSearch"><?php echo __('Search'); ?></label>
          <input name="data[Search][password]" class="required" maxlength="50" type="text" id="SearchPassword"/>
        </div> 
        <div class="submit"><input type="submit" value="search"/></div> 
      </fieldset> 
    </form> 
  </div>
