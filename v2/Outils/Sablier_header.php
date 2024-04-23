  <script
    type="text/javascript"
    src="../JS/jquery-git.js"
  ></script>

  <style type="text/css">
    #overlay{
    	display: none;
		position: fixed;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: black;
		opacity: 0.8;
		z-index:1001;
	}
	
	#overlay-content{ 
		position: absolute;
		display: none;
		width: 420px;
		height: 120px;
		padding: 16px;
		background-color: black;
		z-index:1002;
	}
  </style>
    <script type="text/javascript">

    function sablier() {
          $.fn.center = function () {
				  this.css("position","absolute");
				  this.css("top", Math.max(0, (
				    ($(window).height() - $(this).outerHeight()) / 2) + 
				     $(window).scrollTop()) + "px"
				  );
				  this.css("left", Math.max(0, (
				    ($(window).width() - $(this).outerWidth()) / 2) + 
				     $(window).scrollLeft()) + "px"
				  );
				  return this;
				}
				
				$("#overlay").show();
				$("#overlay-content").show().center();

        }


    $(window).on('load', function() {
        $.fn.center = function () {
				  this.css("position","absolute");
				  this.css("top", Math.max(0, (
				    ($(window).height() - $(this).outerHeight()) / 2) + 
				     $(window).scrollTop()) + "px"
				  );
				  this.css("left", Math.max(0, (
				    ($(window).width() - $(this).outerWidth()) / 2) + 
				     $(window).scrollLeft()) + "px"
				  );
				  return this;
				}
				
				$("#overlay").show();
				$("#overlay-content").show().center();

      });
    

			$(window).on('load', function() {
				$("#overlay").fadeOut();
			});
        
</script>