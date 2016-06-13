<div class="content">
    <h1>Help</h1>
	<h2>*If you experience any problems, try pressing F5, if that does not fix the issue, please dial x6701*</h2>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

	<div class="centerText backgroundColor">
	
    <p>
        To  <span style="font-weight: bold;">create trailers (shuttle, DTX, outside trailers)</span>, click on the "Commercial Trailers" text box.</p>
		<div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/ScreenShot005.jpg" alt="Commercial trailer cloner" /></div>
		<div class="legend centerAlign">
		<h2>Legend</h2>
			<ul>
				<li>Shuttle = <span style="font-weight: bold; color: green;">shXXX</span></li>
				<li>45 footer Shuttle = <span style="font-weight: bold; color: red;">s45</span>shXXX</li>
				<li>48 footer Shuttle = <span style="font-weight: bold; color: #3333CC;">s48</span>shXXX</li>
				<li> DTX trailer = <span style="font-weight: bold; color: SteelBlue;">dtXXX</span></li>
				<li> Outside Trailer = <span style="font-weight: bold; color: orange;">coXXXXX</span></li>
				<li> Outside Vendor = <span style="font-weight: bold; color: orange;">New Penn XXXXXXX </span>(replace New Penn with company name)</li>
				<li> Trailer without skirts = <span style="font-weight: bold; color: yellow;">ns</span> in front of trailer code. eg.: nsco342 or nssh023</li>
				<li> Speciality Trailers = <span style="font-weight: bold; color: purple;">sp</span> in front of traler code. eg. spco234 or spdt334</li>
				<li> <span style="font-weight: bold; color: gray;">Gray doors</span> = 45 footer trailers can only be placed here.</li>
				<li> <span style="font-weight: bold; color:">All trailers</span> = "working" trailers. Trailers that are not in any door and lots but still on or on the way to the complex. Please remember to give the trailer to another complex or 
				delete the trailer that is no longer in your complex or else other complexes cannot use the trailer code!</li>
				<li> <span style="font-weight: bold;">Lots</span> = lots or space where trailers are stored on the complex. (Lots are created by the admin. If more lots are needed, please contact the admin)</li>
			</ul>
		</div>
		<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTYdt.mp4" type="video/mp4">
			Your browser does not support the video tag.
		</video>
     <p><span style="font-weight: bold;">To create a shuttle</span>
       type in "<span style="font-weight: bold;">shXXX</span>" (replace X's with trailer number).
	   <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/ScreenShot004.jpg" alt="Creating a shuttle trailer example" /></div>
	 </p>
	 <p> To <span style="font-weight: bold;">create a 48 foot DT trailers</span>, type in a "<span style="font-weight: bold;">d48</span>" before the DT code. For example: <span style="font-weight: bold;">d48</span>dt851.
	
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/DTCreate.jpg" alt="Creating a 48 footer DT trailer example" /></div>
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/DTMove.jpg" alt="Creating a 48 footer DT trailer example" /></div>
	  d48 trailers will have a <span style="font-weight: bold; color: #66FFFF;">baby blue</span> outlining border. 
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/DTFinal.jpg" alt="Creating a 48 footer shuttle trailer example" /></div>
    </p>
	 <p> To <span style="font-weight: bold;">create a 45 footer trailer</span>, type in a "<span style="font-weight: bold; color: red;">d45</span>" before the shuttle code. For example: <span style="font-weight: bold; color: red;">d45</span>sh565.
	
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/45dtCreate.jpg" alt="Creating a 45 footer DT trailer example" /></div>
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/45dtMove.jpg" alt="Creating a 45 footer DT trailer example" /></div>
	  d45 trailers will have a <span style="font-weight: bold; color: red;">red</span> outlining border. Only d45 trailers can be placed in a grayed out door which are only meant for a 45 footer trailer. 
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/45dtFinal.jpg" alt="Creating a 45 footer shuttle trailer example" /></div>
    </p>
	<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTY45dt.mp4" type="video/mp4">
			Your browser does not support the video tag.
	</video>
	<p> Create a <span style="font-weight: bold;">trailer without a skirt</span>. Type in <span style="font-weight: bold; color: yellow;">ns</span> in front of trailer code. For example: <span style="font-weight: bold; color: yellow;">ns</span>co043.
	<div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/NoSkirtCreate.jpg" alt="Creating a trailer with a skirt" /></div>
	Drag the trailer to the desired door. The trailer will automatically change to have a yellow border.
	<div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/NoSkirtFinal.jpg" alt="Creating a trailer with a skirt" /></div>
	Do the same steps above with <span style="font-weight: bold; color: purple;">sp</span> (Speciality trailers) <span style="font-weight: bold; color: purple;">sp</span>COxxx or <span style="font-weight: bold; color: purple;">sp</span>DTxxx
	</p>
	<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTYnotes.mp4" type="video/mp4">
			Your browser does not support the video tag.
	</video>
	<p> To <span style="font-weight: bold;">access the notes menu of the trailer</span>, click <span style="font-weight: bold;">ONCE</span> on the trailer.
	
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/NotesMenu.jpg" alt="Accessing Notes Menu" /></div>
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/NotesMenuSelect.jpg" alt="Selecting the product from Notes Menu" /></div>
	  While the menu is open, you will not be able to drag any trailer! To close the menu, <span style="font-weight: bold; color: red;">DOUBLECLICK</span> on the trailer. (Ignore any highlighting that may occur)
	  <div class="centerAlign"><img src="<?php echo URL; ?>/public/img/help/NotesMenuClosed.jpg" alt="Notes menu closed displaying the product in the trailer" /></div>
    </p>
	<h2>Outside Trailers</h2>
	<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTYoutside.mp4" type="video/mp4">
			Your browser does not support the video tag.
	</video>
	<h2>Changing trailer status</h2>
	<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTYstatus.mp4" type="video/mp4">
			Your browser does not support the video tag.
	</video>
	<h2>All Trailers Box</h2>
	<video width="640" height="480" controls>
			<source src="<?php echo URL; ?>/public/video/DTYalltrailers.mp4" type="video/mp4">
			Your browser does not support the video tag.
	</video>
	</div>

</div>
