<script type="text/javascript">
//<![CDATA[

// Here's a menu object to control the above list of menu data:
var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');


// I'm applying two at once to listMenu. Delete this to disable!
listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;
//listMenu.animations[listMenu.animations.length] = FSMenu.animClipDown;

var arrow = null;
if (document.createElement && document.documentElement)
{
 arrow = document.createElement('span');
 arrow.appendChild(document.createTextNode('>'));
 arrow.className = 'subind';
}
addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));
//]]>
</script>



<script type="text/javascript">
//<![CDATA[

// With this divMenu object, 'false' means the menu elements are not nested.
var divMenu = new FSMenu('divMenu', false, 'visibility', 'visible', 'hidden');
divMenu.cssLitClass = 'highlighted';
divMenu.animInSpeed = 1;
divMenu.animOutSpeed = 1;

//]]>
</script>