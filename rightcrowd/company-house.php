<div id="details" class="companies-house-content">
      <h4>Companies House </h4>
      <?php 
            $companyRegNo = get_post_meta(get_the_ID(), 'thm_coregno', true);
                    $c = curl_init("https://find-and-update.company-information.service.gov.uk/company/$companyRegNo/filing-history");
                    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                    $html = curl_exec($c);
                    if (curl_error($c))
                         die(curl_error($c));

                    // Get the status code
                    $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
                    curl_close($c);
                    if($status == 200) {               
                    $first_step = explode( '<table id="fhTable" class="full-width-table">' , $html );
                    $second_step = explode("</table>" , $first_step[1] );

                    echo '<table id="fhTable" class="full-width-table companies-house-table">';
                    echo $second_step[0];
                    echo '</table>';
                    }else{
                        echo "No Data Found";
                    }
            ?>
 </div>
<script>
    jQuery(document).ready(function(){
       jQuery('a.download').each(function(){
            var v = jQuery(this).attr('href');
            jQuery(this).attr('href',v);
       }); 
    });
</script>