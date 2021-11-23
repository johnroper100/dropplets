Dropplets v2.2
======================================

## Get Going:
1. Clone this repo
2. Open a shell and navigate to the cloned repo
3. Run the following command `git submodule update --init --recursive`
4. Upload folder to hosting server or run locally with [XAMPP](https://www.apachefriends.org/index.html)
5. Navigate to the URL of your host `https://(your url)/settings`
6. Configure settings and click `Create Your Blog`

## Manage Your Blog:

Go to `https://(your url)/dashboard` and all of the site options will be available.

## Project Goals:

- 30 second install and setup
- Simple post publishing form
- Quick password-only access
- Database not required

## Building Templates

Dropplets has two required files for templates:

- `home.php` is the page that lists the posts
- `post.php` is the page that displays a single post

There are a number of variables available for templates:

- `$siteConfig` is an array containing all of the site information
- `$allPosts` is an array containing all of the posts for page
- `$page` is the current page for pagination
- `$limit` is the number of posts to display per page
- `$post` is an array containing the data for one post