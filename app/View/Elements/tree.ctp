<?php
/**
 * Tree Navigation
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.tree
 * @since         version 2.12.11
 */
?>
<ul class="tree">
  <li class="open node root">
    <div>
      <a href="#" class="control close"><span>close</span></a>
      <a href="#">socials</a>
      <a href="#" class="control more"><span>more</span></a>
    </div>
    <ul>
      <li>
        <div class="node leaf">
          <a href="#" class="with_icon twitter">twitter</a>
        </div>
      </li>
      <li class="closed node root">
        <div>
      		<a href="#" class="control open"><span>open</span></a>
          <a href="#" class="with-icon facebook">facebook</a>
          <a href="#" class="control more"><span>more</span></a>
        </div>
        <ul>
          <li class="node leaf">
            <div>
              <a href="#">page</a>
              <a href="#" class="control more"><span>more</span></a>
            </div>
          </li>
          <li class="node leaf">
            <div>
              <a href="#">group</a>
              <a href="#" class="control more"><span>more</span></a>
            </div>
          </li>
        </ul>
      </li>
      <li class="opened node root">
        <div>
      		<a href="#" class="control close"><span>close</span></a>
          <a href="#" class="with-icon facebook">linked in</a>
          <a href="#" class="control more"><span>more</span></a>
        </div>
        <ul>
          <li class="node leaf">
            <div>
              <a href="#">keke</a>
              <a href="#" class="control more"><span>more</span></a>
            </div>
          </li>
          <li class="node leaf">
            <div>
              <a href="#">cedric</a>
              <a href="#" class="control more"><span>more</span></a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>
