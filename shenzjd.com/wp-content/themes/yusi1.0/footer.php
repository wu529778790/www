</section>
<footer class="footer">
    <div class="footer-inner">
        <div>
            <?php if( dopt('d_track_b') ) echo dopt('d_track'); ?>
        </div>
    </div>
</footer>

<?php
wp_footer();
global $dHasShare;
if($dHasShare == true){
	echo'<script>with(document)0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];</script>';
}
if( dopt('d_footcode_b') ) echo dopt('d_footcode');
?>
</body>
</html>