![Chevrons - A Dropplets Theme](http://bishless.com/iii/chevrons-logo.png)

# What is it?

**Chevrons** is a theme for [Dropplets](https://github.com/Circa75/dropplets) (a simple, markdown-based blogging solution).

# Barely tested

As there is no actual documentation for custom Dropplets themes yet, this one is early in life and hasn't been tested much. Please help me cram it through various browsers and work through issues.

# I think I'm supposed to include a feature list or something

* Chevrons is responsive - even the actual chevron shapes (nod to [this guy](http://jsfiddle.net/apticknor/hyXtR/)).
* The theme uses a couple fonts from Google. Feel free to swap them out for your own choices.
* It also contains icons from [Font Awesome](http://fortawesome.github.io/Font-Awesome/). _Squee!_
* Dropplets operates with two different post statuses - published and draft. Thankfully, with a little additional CSS, this theme will uniquely style posts marked with the `aside` status.

## Technobabble

chevrons makes use of [SASS](http://thesassway.com/) &amp; [Bourbon](http://bourbon.io/). However, I've set the default output of the compiled CSS file (`screen.css`) to `:expanded` and turned on line comments - so it should be pretty easy to jump into customizing the theme even if you're not comfortable with SASS. Those familiar w/ SASS will want to alter the config to produce a compressed `screen.css` file.