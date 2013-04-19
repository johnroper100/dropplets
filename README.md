Dropplets
=========

> Version 1.0.2

A minimalist markdown blogging platform that just works.

## What's Markdown?
Markdown is a text formatting syntax inspired on plain text email. It is extremely simple, memorizable and visually lightweight on artifacts so as not to hinder reading.

> The idea is that a Markdown-formatted document should be publishable as-is, as plain text, without looking like it’s been marked up with tags or formatting instructions.

If you're looking for an awesome Markdown editor, Byword (http://bywordapp.com/) is a great solution for the Mac. They also have an awesome syntax guide which can be found Here (http://bywordapp.com/markdown/syntax.html).

## Installation
Installing Dropplets is pretty easy. Just follow the few steps below.

1. Download the latest version here on GitHub.
2. Extract the zip file.
3. Drag and drop everything to your server wherever you want it to be installed. 
4. Pull up your target site in a web browser and follow the prompts.

**IMPORTANT NOTE:** If you're not brought to the installation screen after this process, you probably didn't get the **.htaccess** file in the root Dropplets directory. These files are usually hidden by default, so you might have to show hidden files to see it.

## Post Formatting
Posts must currently be composed in markdown format, saved as a "*.md". You may include a matching featured image (optional). Here's an example of good post file naming:

- your-post-title.md
- your-post-title.jpg

Post file names are used to structure permalinks. So, a post file saved as "your-post-title.txt" will result in "http://yoursite.com/your-post-title".

Your posts (markdown files) **MUST** be formatted as follows:

    # Your Post Title
    - Post Author
    - Twitter Handle (e.g. "dropplets")
    - 2020/02/02
    - Post Category
    - Post Status (e.g. "published" or "draft")

    Your post text starts here.
    
## About the Dashboard
The Dashboard can be used to publish new posts, edit existing posts, change your blog settings or change your template. To access the Dropplets dashboard, load "http://yoursite.com/dashboard/", obviously changing "yoursite.com" to your websites URL.

## Publishing Posts
Publishing posts is really easy to do within the Dashboard:

1. Login to your Dropplets Dashboard.
2. Drag and drop your post file into the big "drop" zone within the center of your dashboard.

## License
Copyright (c) 2013 Circa75 Media, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.