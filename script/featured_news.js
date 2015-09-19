// functions for changing featured news stories on main page
//
// requires var "storyCount" set by page
var currentStory = 0;
var storyTimeout = 10000;
var storyTimer = setTimeout("next_story()", storyTimeout);
var storyTimerRunning = 1;

function toggle_story( storyNum, clicked )
{
     for( var i = 0; i < storyCount; i++ )
	  eval( 'document.getElementById("featured_story_'+i+'").style.display = "none";' );
	
	eval( 'document.getElementById("featured_story_'+storyNum+'").style.display = "block";' );
	currentStory = storyNum;
	
	if( clicked && storyTimerRunning )
	{
	  clearTimeout( storyTimer );	
	  storyTimerRunning = 0;
	}
}

function next_story( )
{
  var nextStory = (currentStory + 1) % storyCount;
  toggle_story( nextStory, 0 );
  storyTimer = setTimeout("next_story()", storyTimeout);
}