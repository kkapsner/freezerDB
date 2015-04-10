<body<?php if ($this->bodyClass){ echo ' class="' . $this->bodyClass . '"';}?>>
	<header>
		<div id="headContent">
<?php echo $this->headContent;?>
<?php $this->mainNavigation->view($context, true);?>
		</div>
	</header>
	<article id="main">
		<ul id="error">
<?php echo $this->getErrorLi();?>
		</ul>
		<section id="content"<?php if($this->contentIndent) echo ' style="padding-left: ' . $this->contentIndent . ';"';?>>
<?php echo $this->content;?>
		</section>
	</article>
	<footer>
		<section>
<?php echo $this->footContent;?>
		</section>
	</footer>
</body>