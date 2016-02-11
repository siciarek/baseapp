<?php

namespace Application\MainBundle\Common\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

class TextHelper extends Helper
{
    /**
     * Truncates +text+ to the length of +length+ and replaces the last three characters with the +truncate_string+
     * if the +text+ is longer than +length+.
     */
    public function truncate($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
    {
        if ($text == '') {
            return null;
        }

        if (strlen($text) > $length) {
            $truncate_text = substr($text, 0, $length - strlen($truncate_string));
            if ($truncate_lastspace) {
                $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
            }

            return $truncate_text.$truncate_string;
        } else {
            return $text;
        }
    }

    /**
     * Highlights the +phrase+ where it is found in the +text+ by surrounding it like
     * <strong class="highlight">I'm a highlight phrase</strong>. The highlighter can be specialized by
     * passing +highlighter+ as single-quoted string with \1 where the phrase is supposed to be inserted.
     * N.B.: The +phrase+ is sanitized to include only letters, digits, and spaces before use.
     */
    public function highlight($text, $phrase, $highlighter = '<strong class="highlight">\\1</strong>')
    {
        if ($text == '') {
            return null;
        }

        if ($phrase == '') {
            return $text;
        }

        return preg_replace('/('.preg_quote($phrase, '/').')/i', $highlighter, $text);
    }

    /**
     * Extracts an excerpt from the +text+ surrounding the +phrase+ with a number of characters on each side determined
     * by +radius+. If the phrase isn't found, nil is returned. Ex:
     *   excerpt("hello my world", "my", 3) => "...lo my wo..."
     * If +excerpt_space+ is true the text will only be truncated on whitespace, never inbetween words.
     * This might return a smaller radius than specified.
     *   excerpt("hello my world", "my", 3, "...", true) => "... my ..."
     */
    public function excerpt($text, $phrase, $radius = 100, $excerpt_string = '...', $excerpt_space = false)
    {
        if ($text == '' || $phrase == '') {
            return null;
        }

        $found_pos = strpos(strtolower($text), strtolower($phrase));
        if ($found_pos !== false) {
            $start_pos = max($found_pos - $radius, 0);
            $end_pos = min($found_pos + strlen($phrase) + $radius, strlen($text));
            $excerpt = substr($text, $start_pos, $end_pos - $start_pos);

            $prefix = ($start_pos > 0) ? $excerpt_string : '';
            $postfix = $end_pos < strlen($text) ? $excerpt_string : '';

            if ($excerpt_space) {
                // only cut off at ends where $exceprt_string is added
                if ($prefix) {
                    $excerpt = preg_replace('/^(\S+)?\s+?/', ' ', $excerpt);
                }
                if ($postfix) {
                    $excerpt = preg_replace('/\s+?(\S+)?$/', ' ', $excerpt);
                }
            }

            return $prefix.$excerpt.$postfix;
        }
    }

    /**
     * Word wrap long lines to line_width.
     */
    public function wrap($text, $line_width = 80)
    {
        return preg_replace('/(.{1,'.$line_width.'})(\s+|$)/s', "\\1\n", preg_replace("/\n/", "\n\n", $text));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ewz_text';
    }
}
