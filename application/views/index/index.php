<!-- CSS -->

<!-- JS -->
<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery.jcarousel.min.js"></script>

<script>
	$(function() {
    $('.jcarousel')
        .jcarousel({
            wrap: 'circular'
        })
        .jcarouselAutoscroll({
            interval: 3000,
            target: '+=1',
            autostart: true
        });
		 $('.jcarousel-control-prev')
                    .on('jcarouselcontrol:active', function() {
                        $(this).removeClass('inactive');
                    })
                    .on('jcarouselcontrol:inactive', function() {
                        $(this).addClass('inactive');
                    })
                    .jcarouselControl({
                        target: '-=1'
                    });

            $('.jcarousel-control-next')
                    .on('jcarouselcontrol:active', function() {
                        $(this).removeClass('inactive');
                    })
                    .on('jcarouselcontrol:inactive', function() {
                        $(this).addClass('inactive');
                    })
                    .on('click', function(e) {
                        e.preventDefault();
                    })
                    .jcarouselControl({
                        target: '+=1'
                    });

            $('.jcarousel-pagination')
                    .on('jcarouselpagination:active', 'a', function() {
                        $(this).addClass('active');
                    })
                    .on('jcarouselpagination:inactive', 'a', function() {
                        $(this).removeClass('active');
                    })
                    .on('click', function(e) {
                        e.preventDefault();
                    })
                    .jcarouselPagination({
                        item: function(page) {
                            return '<a href="#' + page + '">' + page + '</a>';
                        }
                    });
	    var $el = null, leftPos = null, newWidth = null;
        
		
	});
</script>
<div class="content">

  
	<div class="centerAlign"><img src="<?php echo URL; ?>/public/img/dty.png" alt="Dart Trailer and Yard Management System" />	</div>
    <h1>Dart Trailer and Yard Management System</h1>
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

<div class="context">
<div class="jcarousel-wrapper">
                <div class="jcarousel">
                    <ul>
						<li><img src="<?php echo URL; ?>/public/img/carousel/dart.jpg" width="600" height="400" alt=""></li>
                        <li><img src="<?php echo URL; ?>/public/img/carousel/truck1.jpg" width="600" height="400" alt=""></li>
                        <li><img src="<?php echo URL; ?>/public//img/carousel/truck2.jpg" width="600" height="400" alt=""></li>
                        <li><img src="<?php echo URL; ?>/public/img/carousel/truck3.jpg" width="600" height="400" alt=""></li>
                        <li><img src="<?php echo URL; ?>/public/img/carousel/truck4.jpg" width="600" height="400" alt=""></li>
                        <li><img src="<?php echo URL; ?>/public/img/carousel/truck5.jpg" width="600" height="400" alt=""></li>
						<li><img src="<?php echo URL; ?>/public/img/carousel/truck6.jpg" width="600" height="400" alt=""></li>
                        
                    </ul>
                </div>

                <p class="photo-credits">
                    Photos by <a href="http://www.dartcontainer.com">Ioann Trubachev</a>
                </p>

                <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next">&rsaquo;</a>
                
                <p class="jcarousel-pagination">
                    
                </p>
            </div>
</div>
    <h2>Yard Management highlights:</h2>
    <p>
        Dart Yard Management System (DTY) was developed in house by Dart's own talented programmers. DTY was  developed with end-user input and end-user testing.  
        DTY 
        <span style='font-weight: bold;'>solves trailer tracking within Dart complexes </span>,
        allowing multiple users to view trailer moves in real time.
        
    </p>

</div>
