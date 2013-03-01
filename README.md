Dropplets
=========

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
Posts must currently be composed in markdown format, saved as a "*.txt" and include a matching featured image as follows:

- your-post-title.txt
- your-post-title.jpg

Post file names are used to structure permalinks. So, a post file saved as "your-post-title.txt" will result in "http://yoursite.com/your-post-title".

Your posts **MUST** be formatted as follows:

    # Your Post Title
    - 2020/02/02
    - Post Category

    Your post text starts here.

## Important Note
Please note that this is very much a work in progress, so use at your own risk. Also, I'm not a developer, so there are most likely many ways this could be improved. There is SO MUCH that needs to be done, so please be kind :) 

## License
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
