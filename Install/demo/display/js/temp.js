/**
 * Created with JetBrains PhpStorm.
 * User: Alex
 * Date: 21/11/22
 * Time: 13:10
 * To change this template use File | Settings | File Templates.
 */
/*<div id="content" style="width: 150px">hello how are you? <h1>
    <h1>hello</h1> how are you? hello how are you?</div>*/

$(document).ready(function(){

    calculate = function(obj){
        other = obj.clone();
        other.html('a<br>b').hide().appendTo('body');
        size = other.height() / 2;
        other.remove();
        return obj.height() /  size;
    }

    n = calculate($('#content'));
    alert(n + ' lines');
});





/*
 #container {
 line-height: 2em;
 width: 200px;
 }

 #lines {
 box-sizing: border-box;
 padding: 2em;
 }
<div id="container">
    <div id="lines">
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
    </div>
</div>*/
function calculateLineCount(element) {
    var lineHeightBefore = element.css("line-height"),
        boxSizing        = element.css("box-sizing"),
        height,
        lineCount;

    // Force the line height to a known value
    element.css("line-height", "1px");

    // Take a snapshot of the height
    height = parseFloat(element.css("height"));

    // Reset the line height
    element.css("line-height", lineHeightBefore);

    if (boxSizing == "border-box") {
        // With "border-box", padding cuts into the content, so we have to subtract
        // it out
        var paddingTop    = parseFloat(element.css("padding-top")),
            paddingBottom = parseFloat(element.css("padding-bottom"));

        height -= (paddingTop + paddingBottom);
    }

    // The height is the line count
    lineCount = height;

    return lineCount;
}

alert(calculateLineCount($('#lines')));