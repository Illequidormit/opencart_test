<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <ul>
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['isCheked'] == 1) { ?>
        <?php if ($category['children']) { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <?php if ($child['isCheked'] == 1) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
                <?php } ?>
              </ul>
              <?php } ?>
        </li>
        <?php } else { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </ul>
  </div>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
