<body>
<?php $this->load->view('front/nav'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
              <h1><?=$code?></h1>
              <p><?=$msg?></p>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/footer'); ?>
</body>
</html>