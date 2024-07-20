<?php
class Text {

    private static $_numList = array(
        array(' ### ', '#   #', '#   #', '#   #', '#   #', '#   #', ' ### '),
        array('  # ', ' ## ', '# # ', '  # ', '  # ', '  # ', '####'),
        array(' ### ', '#   #', '    #', '   # ', '  #  ', ' #   ', '#####'),
        array('#####', '    #', '   # ', '  ## ', '    #', '##  #', ' ### '),
        array('   # ', '  ## ', ' # # ', ' # # ', '#####', '   # ', '  ###'),
        array('####', '#   ', '#   ', '### ', '   #', '   #', '### '),
        array('  ## ', ' #   ', '#    ', '#### ', '#   #', '#   #', '#####'),
        array('#####', '    #', '   # ', '  #  ', '  #  ', '  #  ', '  #  '),
        array(' ### ', '#   #', '#   #', ' ### ', '#   #', '#   #', ' ### '),
        array(' ## ', '#  #', '#  #', ' ###', '   #', '   #', ' ###')
    );
    private static $_symbols = array(
        '+' => array('      ', '      ', '  ##  ', '######', '  ##  ', '      ', '      '),
        '-' => array('      ', '      ', '      ', '######', '      ', '      ', '      '),
        '*' => array('      ', '      ', ' #### ', '######', ' #### ', '      ', '      '),
        '/' => array('     ', '    #', '   # ', '  #  ', ' #   ', '#    ', '     '),
        '=' => array('      ', '      ', '######', '      ', '######', '      ', '      '),
        '.' => array('  ', '  ', '  ', '  ', '  ', '##', '##'),
    );
    private static $_type = array('#', '0', '$', '@', '%');

    public static function init($num) {
        $num = (string) $num;

        $type = self::$_type[rand(0, (count(self::$_type) - 1))];

        for ($line = 0; $line < 7; $line++) {
            for ($i = 0; $i < strlen($num); $i++) {
                $one = $num[$i];
                if (is_numeric($one)) {
                    $data = str_replace(' ', '&nbsp;', self::$_numList[$one][$line]);
                } else {
                    $data = str_replace(' ', '&nbsp;', self::$_symbols[$one][$line]);
                }

                $result .= '&nbsp;' . str_replace('#', $type, $data) . '&nbsp;';
            }
            $result .= '<br>';
        }

        return $result;
    }

}
