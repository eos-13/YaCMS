��    3      �  G   L      h  �   i  +  8  w   d  �   �  /   w     �  $   �  ;   �  \   '  6   �  9   �  8   �  �   .	  (   �	  V   
  -   Y
  1   �
  8   �
  $  �
  �               (   (     Q     e     y     �     �  -  �  5   �  �   /  4   �  e   
  +   p  1   �  \   �  !   +  %   M  ^  s  k  �  �  >  !         5  '   V     ~     �     �  e   �     ?  !   O  @  q  �   �  +  �  w   �  �   %  /   �     �  $     ;   4  \   p  5   �  8     8   <  �   u  (     V   H  -   �  1   �  8   �  $  8  �   ]      \!     _!  (   n!     �!     �!     �!     �!     �!  -  "  5   E#  �   {#  4    $  e   U$  +   �$  1   �$  \   %  !   v%  %   �%  ^  �%  k  '  �  �(  !   ^*      �*  '   �*     �*     �*     +  e   $+     �+  !   �+                
   -                    1   )      %                         '                &   /          ,                        3   	   +   .          0               !                   $      (      #                            2       "       *    A custom function for ajax. It defaults to $.ajax, if defined the provided function will receive the same options of $.ajax. It allows other ajax implementations such as ajaxQ or something based on promises A string containing the URL to which the request is sent or a function that returns an url. If this option is a function, it will receive a reference of track, the page number and the perPage value, if not RemoteContent will try to replace the substrings {page} and {perPage} with the current values Allows your track to foward one page by "duration option" time. It automatically disabled when mouse is over the track. Animation function used by SilverTrack. This function will be used by $.animate, so you could use any plugin that adds easing functions. Ex: jQuery Easing Class applied to disable the navigation element Class applied to each "bullet" Class applied to the active "bullet" Class applied to the cloned items that makes track circular Configure the css3 properties to allow hardware acceleration in the parent of the container. Configure the css3 transition-delay to the container.  Configure the css3 transition-duration to the container.  Configure the css3 transition-property to the container. Configure the css3 transition-timing-function to the container, it already converts the easing names (easeInOutQuart, easeInCubic, etc) to proper cubic-bezier functions.  Container to append the navigation links Data to be sent to the server. This parameter is sent to option "data" of ajaxFunction Element that will receive the "next" function Element that will receive the "previous" function If it will adjust the track height after each pagination If set this function will be used instead of $.animate. The function will receive movement, duration, easing and afterCallback. The movement object will be {left: someNumber} or {height: someNumber}. For an example of how to replace the animation function take a look at css3 animation plugin It determines if RemoteContent will fetch the first page after the track starts or just when the user navigate to a page without cache. You will usually set this to false when you render your first page with the track and get the other items through ajax OK On ready event Orientation mode, horizontal or vertical Silver Track Plugin Silver track Plugin Silver track js in action Silver track misc Plugin The amount of items to display The amount of pages that will be prefetched ahead of time. It is based on current page so if you have configured \"prefetchPages: 2\" it will start loading 3 pages (the first + 2) and will keep the distance of 2 pages until the end, so when the current page is 2 there will be 4 loaded pages and so on The css class name that will be used to find the item The delay used between the transitions, note that delay it is not the same as the duration, this is the time that the browser will wait before the animation starts.  The direction of pagination (horizontal or vertical) The direction of the animation (x or y). To animate in "y" axis you will need the mode "vertical" too The duration used by the animation function The resolution names configured in responsiveHub. The type of request to make ("POST" or "GET"). This parameter is sent direct to ajaxFunction The unit used for the animations. The unit used for the delay property. This option configures the delay of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the delay of the slide animation, make sure that both will work. It automatically fallback to the option slideDelay This option configures the duration of the height adjustment animation if the property autoHeight of track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the duration of the slide animation, make sure that both will work. It automatically fallback to the option duration of the track This option configures the easing function of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option convert the easing name (easeInOutQuart, easeInCubic, etc) to the proper cubic-bezier function and is configured with the easing function of the slide animation, make sure that both will work. It automatically fallback to the option easing of the track Use silver_track bullet navigator Use silver_track css 3 animation Use silver_track css circular animation Use silver_track navigator Use silver_track remote content Use silver_track responsive hub When set to true, the plugin will consider the first page as a cover and will consider it as one item on change event time to auto play in milliseconds Project-Id-Version: PACKAGE VERSION
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2015-04-08 16:11+0200
PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE
Last-Translator: FULL NAME <EMAIL@ADDRESS>
Language-Team: LANGUAGE <LL@li.org>
Language: 
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: 8bit
 A custom function for ajax. It defaults to $.ajax, if defined the provided function will receive the same options of $.ajax. It allows other ajax implementations such as ajaxQ or something based on promises A string containing the URL to which the request is sent or a function that returns an url. If this option is a function, it will receive a reference of track, the page number and the perPage value, if not RemoteContent will try to replace the substrings {page} and {perPage} with the current values Allows your track to foward one page by "duration option" time. It automatically disabled when mouse is over the track. Animation function used by SilverTrack. This function will be used by $.animate, so you could use any plugin that adds easing functions. Ex: jQuery Easing Class applied to disable the navigation element Class applied to each "bullet" Class applied to the active "bullet" Class applied to the cloned items that makes track circular Configure the css3 properties to allow hardware acceleration in the parent of the container. Configure the css3 transition-delay to the container. Configure the css3 transition-duration to the container. Configure the css3 transition-property to the container. Configure the css3 transition-timing-function to the container, it already converts the easing names (easeInOutQuart, easeInCubic, etc) to proper cubic-bezier functions. Container to append the navigation links Data to be sent to the server. This parameter is sent to option "data" of ajaxFunction Element that will receive the "next" function Element that will receive the "previous" function If it will adjust the track height after each pagination If set this function will be used instead of $.animate. The function will receive movement, duration, easing and afterCallback. The movement object will be {left: someNumber} or {height: someNumber}. For an example of how to replace the animation function take a look at css3 animation plugin It determines if RemoteContent will fetch the first page after the track starts or just when the user navigate to a page without cache. You will usually set this to false when you render your first page with the track and get the other items through ajax OK On ready event Orientation mode, horizontal or vertical Silver Track Plugin Silver track Plugin Silver track javascript library Silver track misc plugin The amount of items to display The amount of pages that will be prefetched ahead of time. It is based on current page so if you have configured \"prefetchPages: 2\" it will start loading 3 pages (the first + 2) and will keep the distance of 2 pages until the end, so when the current page is 2 there will be 4 loaded pages and so on The css class name that will be used to find the item The delay used between the transitions, note that delay it is not the same as the duration, this is the time that the browser will wait before the animation starts. The direction of pagination (horizontal or vertical) The direction of the animation (x or y). To animate in "y" axis you will need the mode "vertical" too The duration used by the animation function The resolution names configured in responsiveHub. The type of request to make ("POST" or "GET"). This parameter is sent direct to ajaxFunction The unit used for the animations. The unit used for the delay property. This option configures the delay of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the delay of the slide animation, make sure that both will work. It automatically fallback to the option slideDelay This option configures the duration of the height adjustment animation if the property autoHeight of track is set to true. It might also be done directly through the css file but be aware that this option is correctly configured with the duration of the slide animation, make sure that both will work. It automatically fallback to the option duration of the track This option configures the easing function of the height adjustment animation if the property autoHeight of the track is set to true. It might also be done directly through the css file but be aware that this option convert the easing name (easeInOutQuart, easeInCubic, etc) to the proper cubic-bezier function and is configured with the easing function of the slide animation, make sure that both will work. It automatically fallback to the option easing of the track Use silver_track bullet navigator Use silver_track css 3 animation Use silver_track css circular animation Use silver_track navigator Use silver_track remote content Use silver_track responsive hub When set to true, the plugin will consider the first page as a cover and will consider it as one item On change event Time to auto play in milliseconds 