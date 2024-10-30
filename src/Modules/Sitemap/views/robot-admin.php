<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/2/15
 * Time: 4:00 PM
 */
?>
<script type="application/javascript" xmlns="http://www.w3.org/1999/html">
    (function($){
        $(document).ready(function(){
            $('#blog_public').on( "click", function (){
                if ($(this).prop("checked")) {
                    $('#ib_robots_settings').prop('readonly',true);
                } else {
                    $('#ib_robots_settings').prop('readonly',false);
                }
            });
        });
    })(jQuery);
</script>
<div id="ib_robots">
    <div class="ib_row">
        <div class="ib-column ib-column-5 ib-admin-box">
            <form method="post" action="<?php echo admin_url("admin-post.php"); ?>">
            <div class="ib-row ib-th">
                Robots.txt File
            </div>
            <div class="ib-row ib-td">
                <textarea name="ib_robots_settings" id="ib_robots_settings" rows="10" <?php echo (!$public)?'readonly':'';?> class="large-text"><?php echo esc_textarea($content);?></textarea>
                <p class="description">
                    The content of your robots.txt file.
                </p>
                Search Engine Visibility <input type="checkbox" <?php echo (!$public)?'checked':'';?> value="0" id="blog_public" name="blog_public">
                <label for="blog_public">Discourage search engines from indexing this site</label>
            </div>
            <div class="ib-row">
                <?php wp_nonce_field('ib-save-robots-settings','ib-save-robots-settings-nonce'); ?>
                <input type="hidden" name="action" value="ib_update_robots">
                <div class="fr ib-td">
                    <input class="ib-button" value="Update options" type="submit">
                </div>
                <div class="clear"></div>
            </div>
            </form>
        </div>
        <div class="ib-column ib-column-7 ib-admin-box">
            <div class="ib-row ib-th">
                What is a Robots.txt file and how to use it?
            </div>
            <div class="ib-td">
                <h3>What it is:</h3>
                <p>Web site owners use the /robots.txt file to give instructions about their site to web robots; this is called The Robots Exclusion Protocol.</p>
                <h3>How it works:</h3>
                <p>A robot wants to visit a Web site URL, say http://www.example.com/welcome.html. Before it does so, it firsts checks for http://www.example.com/robots.txt, and finds:
                    <blockquote>
                    User-agent: *<br />
                    Disallow: /
                    </blockquote>
                    The "User-agent: *" means this section applies to all robots. The "Disallow: /" tells the robot that it should not visit any pages on the site.
                    <h4>To specify a directory:</h4>
                    <blockquote>
                        User-agent: *<br />
                        Disallow: /my_directory/
                    </blockquote>
                    <h4>To specify a page you add a line like this:</h4>
                    <blockquote>
                        User-agent: *<br />
                        Disallow: /my_page.html
                    </blockquote>
                    This will tell the crawler to not crawl any pages in the "my_directory" path.
                    <h4>A robots.txt can also inform the crawler whe to find your sitemap.xml:</h4>
                    <blockquote>
                        User-agent: *<br />
                        http://example.com/sitemap.xml
                    </blockquote>
                    Notice the lack of the "Disallow:".
                </p>
                <h3>Important considerations when using /robots.txt:</h3>
                <p>robots can ignore your /robots.txt. Especially malware robots that scan the web for security vulnerabilities, and email address harvesters used by spammers will pay no attention.
                    the /robots.txt file is a publicly available file. Anyone can see what sections of your server you don't want robots to use.
                    So don't try to use /robots.txt to hide information.
                    <br /><br />
                    For more information see <a href="http://inboundbrew.com/2015/11/what-is-robots-txt-and-sitemap-xml-management/" title="What is robots.txt and Sitemap.xml Management?">Robots & Sitemap.xml</a>
                </p>
            </div>
        </div>
    </div>
</div>