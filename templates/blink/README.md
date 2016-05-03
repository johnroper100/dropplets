# Bink: a theme for Dropplets

> adj: 1. (slang) refers to a sharp dresser or swell looker. see also: dapper

This theme is a single-author fork of [Jason Schuller][]'s `simple` theme that is packaged with [Dropplets 1.5][], incorporating some styling from [Simple Dark][] by [Chris Reynolds][] and other places. 

## Features:

* Intro uses a separately-styled <header> tag
* Optional buttons in the intro
* Custom post types, including asides and [reveal.js][]

### Custom post types

Post types are set in the header. Currently supported are:

* `draft`
    * Hidden by Dropplets
* `published`
    * Square post thumbnail
    * Supports all markdown that Dropplets supports
* `feature`
    * White background for content
    * Background image defined in `custom/style.css`
    * But seriously, use this as a template to make your own feature posts
* `aside`
    * No thumbnail or decoration on post meta
    * No post headline on front page
    * No "Continue Reading" button on front page
    * No way to access the post itself at all
    * It's just `$post_content` on the front page
* `revealjs`
    * Includes reveal.js and relevant stylesheets
    * Put reveal.js `<section>...</section>` HTML after Dropplets Markdown post header, before Markdown body of article.
    * Separate `<section>...</section>` from Markdown body of article with eight dashes: `--------` (the Markdown `<hr>` equivalent)
    * Has "Click for slide deck &rarr;" button on front page
    
Post types are set by changing the post status:

    # Post title 
    - Post author
    - Post author's twitter @
    - YYYY-MM-DD
    - category
    - draft/published/feature/aside/revealjs
    
### Intro buttons 

Buttons that go in the intro. See `custom/intro-buttons.php`. Leave the file blank after `?>` for no buttons. 

### Reveal.js

Your `post.md` file should look like this:

    # Post title
    - Post author
    - Post author's twitter @
    - YYYY-MM-DD
    - category
    - revealjs

    <section>
        <h1>Reveal.js</h1>
    </section>

    ----‐---

    > Markdown! This content is included in the template with
    > `<?php echo(Markdown($markdown)); ?>` and some parsing.

The eight dashes are mandatory, and will not show up in your post. If you have "--------" in your post before that line, *that* is where the slideshow will end and where your Markdown()'d post will start. Try to escape them with other markup.