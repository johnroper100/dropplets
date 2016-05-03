<div id="comments"></div>
<script src="http://widgets.twimg.com/j/1/widget.js"></script>
<link href="http://widgets.twimg.com/j/1/widget.css" type="text/css" rel="stylesheet">
<script>
new TWTR.Widget({
  type: 'search',
  search: '<?php echo $post_link ?>',
  id: 'comments',
  loop: false,
  subject: 'Comments',
  width: 600,
  height: 300,
  theme: {
    shell: {
      background: '#fff',
      color: '#404040'
    },
    tweets: {
      background: '#fff',
      color: '#404040',
      links: '#303030'
    }
  }
}).render().start();
</script>