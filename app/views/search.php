<?php
/**
 * @search the search string
 * Renders the search form
 */
?>
 
 <form name="search" method="POST">
     <div class="form-group">
         <label>Type your search and press the button:</label>
         <p><input name="search" type="text" value="<?= $search ?>" /></p>
     </div>
     <div class="form-group">
         <button type="submit">Submit</button>
     </div>
 </form>