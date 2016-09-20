### A Simple Blog

Simple filesystem based blog site using PHP, HTML, CSS and Markdown.

__PHP__

I used [simple-blog](https://github.com/yakamok/simple-blog) as an inspiration and [Parsedown](http://parsedown.org/)
to parse the ```*.md``` files used as a content for the blog.

For implementing pagination I modified for my needs function ```paginate_links()```
which is found from [WordPress core](https://core.trac.wordpress.org/browser/trunk/src/wp-includes/general-template.php).

__HTML/CSS__ 

I created a minimal ```style.css``` by modifying code from [Skeleton](http://getskeleton.com/) 
and from [Bootstrap 4](http://v4-alpha.getbootstrap.com/). I took the Skeleton stylesheet, modified it and added 
the grid system for ```col-sm-*```from Bootstrap 4. 

I also utilized and modified some code snippets from [Foundation's](http://zurb.com/building-blocks)
building blocks mainly for the hero, footer and pagination. For the css resets I used [normalize.css](https://necolas.github.io/normalize.css/).

__Photo Credits__

The hero background image comes from [pexels.com](https://www.pexels.com/).
