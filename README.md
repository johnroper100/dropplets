Dropplets
=========

> Version 1.0.2

A minimalist markdown blogging platform that just works.

## What's Markdown?
Markdown is a text formatting syntax inspired on plain text email. It is extremely simple, memorizable and visually lightweight on artifacts so as not to hinder reading.

> The idea is that a Markdown-formatted document should be publishable as-is, as plain text, without looking like it’s been marked up with tags or formatting instructions.

If you're looking for an awesome Markdown editor, Byword (http://bywordapp.com/) is a great solution for the Mac. They also have an awesome syntax guide which can be found Here (http://bywordapp.com/markdown/syntax.html).

## Installation
Dropplets is compatible with most server configurations and can be accomplished typically in under a minute using the few step-by-step instructions below:

1. Download the latest version here on [GitHub](https://github.com/circa75/dropplets/archive/master.zip) and then extract the downloaded zip file.
3. Upload the entire contents of the extracted zip file to your web server wherever you want Dropplets to be installed. 
4. Pull up your site in any modern web browser and follow the installation prompts. For instance, if you uploaded Dropplets to **yoursite.com/blog/**, load **yoursite.com/blog/** in your browser.

**IMPORTANT NOTE:** If you're not brought to the installation screen after this process, you probably didn't upload the **.htaccess** file in the root Dropplets directory. These files are usually hidden by default in most file browsers and ftp clients, so you might have to show hidden files in order to see it. We plan on improving this process in the future.

## Writing Your First Post
With Dropplets, you write your posts offline in any Markdown compatible application then upload your completed post within the Dropplets dashboard (explained below). Please note that all posts must be composed in markdown format and saved with the **.md** file extension. For instance, if your post title is **My First Blog Post**, your post file should look like this:

- my-first-blog-post.md

Some templates include the ability to add a post image or thumbnail along with your post in which should match your post file name like this:

- my-first-blog-post.jpg

Post file names are used to structure "permalinks". So, a post file saved as "my-first-blog-post.md" will result in **yoursite.com/my-first-blog-post** as the post permalink.

All posts for Dropplets **MUST** be composed using the following format:

    # Your Post Title
    - Post Author Name (e.g. "Dropplets")
    - Post Author Twitter Handle (e.g. "dropplets")
    - 2020/02/02
    - Post Category
    - Post Status (e.g. "published" or "draft")

    Your post text starts here.
    
## About the Dashboard
The Dropplets Dashboard can be used to publish new posts, edit existing posts, change your blog settings or change your template. To access the Dropplets dashboard, load **http://yoursite.com/dashboard/**, obviously changing **yoursite.com** to your own website URL.

## Publishing Posts
Publishing posts is really easy to do within the Dropplets Dashboard:

1. Login to your Dropplets Dashboard (e.g. **yoursite.com/dashboard/**).
2. Drag and drop your post file into the "drop" zone (the droplet) within the center of your dashboard.

## Editing Existing Posts
Editing existing posts that have been uploaded and published to Dropplets is really easy to do. Simply re-upload your edited post file within the Dropplets Dashboard using the steps above. Doing this will automatically overwrite the existing post.

## License
Copyright (c) 2013 Circa75 Media, LLC

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.