@page funcunit.actions Actions
@parent FuncUnit 2

Actions are used to simulate user behavior such as clicking, typing, moving the mouse.

## Commands

 - [FuncUnit.open open] - opens a page.
 - [FuncUnit.prototype.click click] - clicks an element (mousedown, mouseup, click).
 - [FuncUnit.prototype.dblclick dblclick] - two clicks followed by a dblclick.
 - [FuncUnit.prototype.rightClick rightClick] - a right mousedown, mouseup, and contextmenu.
 - [FuncUnit.prototype.type type] - types characters into an element.
 - [FuncUnit.prototype.move move] - mousemove, mouseover, and mouseouts from one element to another.
 - [FuncUnit.prototype.drag drag] - a drag motion from one element to another.
 - [FuncUnit.prototype.scroll scroll] - scrolls an element.

Actions run asynchronously, meaning they do not complete all their events immediately.  
However, each action is queued so that you can write actions (and waits) linearly.

@codestart
S('textarea').click().type("Hello World");
  
S('.resizer').drag("+20 +20");
@codeend

## Common mistake

Almost always before performing an action, you should perform a wait that makes sure the 
element you're operating on is ready.  A common pattern is calling visible before most actions.

@codestart
S(".foo").visible().click()
@codeend

Without waits, tests may intermittently fail because of timing conditions. When click runs, it immediately 
simulates a click on the given element.  Often, tests are triggering app behavior that renders elements 
in the page. If that element isn't present before the action runs, the test will fail.