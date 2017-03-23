<?php
/**
 * @package dompdf
 * @link    http://www.dompdf.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: dompdf_image_exception.cls.php,v 1.1 2013/11/28 08:02:32 toanlk Exp $
 */

/**
 * Image exception thrown by DOMPDF
 *
 * @package dompdf
 */
class DOMPDF_Image_Exception extends DOMPDF_Exception {

  /**
   * Class constructor
   *
   * @param string $message Error message
   * @param int $code Error code
   */
  function __construct($message = null, $code = 0) {
    parent::__construct($message, $code);
  }

}
