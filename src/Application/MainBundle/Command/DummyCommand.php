<?php

namespace Application\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DummyCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('dummy')
                ->setDescription('Symfony 2 dummy command.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $filename = __DIR__ . '/../Resources/doc/_static/patterns.txt';
        $content = file_get_contents($filename);

        preg_match_all('/\n(\d\d\d\d)\n/', $content, $matches);

        $tcontent = trim($content);
        $temp = explode("\n\n", $tcontent);

        $result = [];
        $patterns = [];
        $positions = [];

        foreach ($temp as $t) {

            $t = trim($t);
            $rows = explode("\n", $t);
            $key = array_shift($rows);

            if (is_numeric($key) === false) {
                throw new \Exception('KEY: ' . $key);
            }

            if (!array_key_exists($key, $result)) {
                $result[$key] = [];
            }

            foreach ($rows as $row) {
                $trow = explode('-', $row);
                $ikey = array_shift($trow);
                $value = implode('-', $trow);


                if (is_numeric($ikey) === false) {
                    throw new \Exception('IKEY: ' . $ikey);
                }

                $result[$key][$ikey] = $value;
            }
        }

        $sets = [
            '5577' => [
                [ 'Overshift' => 3,],
                [ 'Agent' => 4,],
                [ 'Overshift' => 3,],
                [ 'Agent' => 4,],
                [ 'Overshift' => 3,],
                [ 'Tight' => 1,],
                [ 'Counselor' => 3,],
                [ 'Appraiser' => 3,],
                [ 'Tight' => 1,],
                [ 'Inspirational' => 3,],
                [ 'Appraiser' => 3,],
                [ 'Inspirational' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Inspirational' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Inspirational' => 4,],
            ],
            '5477' => [
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Creative' => 3,],
                [ 'Tight' => 1,],
                [ 'Result-Oriented' => 3,],
                [ 'Creative' => 3,],
                [ 'Result-Oriented' => 4,],
                [ 'Creative' => 3,],
                [ 'Result-Oriented' => 4,],
                [ 'Creative' => 3,],
                [ 'Result-Oriented' => 4,],
            ],
            '5377' => [
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
            ],
            '5277' => [
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
            ],
            '5177' => [
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Investigator' => 3,],
                [ 'Archiever' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
                [ 'Creative' => 3,],
                [ 'Developer' => 4,],
            ],
            '4777' => [
                [ 'Practitioner' => 3,],
                [ 'Agent' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
            ],
            '4677' => [
                [ 'Practitioner' => 3,],
                [ 'Agent' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
            ],
            '4577' => [
                [ 'Practitioner' => 3,],
                [ 'Agent' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Agent' => 4,],
                [ 'Practitioner' => 2,],
                [ 'Tight' => 1,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Tight' => 1,],
                [ 'Counselor' => 3,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
            ],
            '4477' => [
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 2,],
                [ 'Tight' => 2,],
                [ 'Specialist' => 3,],
                [ 'Objective Thinker' => 2,],
                [ 'Tight' => 3,],
                [ 'Undershift' => 2,],
                [ 'Objective Thinker' => 3,],
                [ 'Tight' => 2,],
                [ 'Undershift' => 2,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
            ],
            '4377' => [
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Tight' => 2,],
                [ 'Undershift' => 2,],
                [ 'Objective Thinker' => 3,],
                [ 'Tight' => 2,],
                [ 'Undershift' => 2,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
            ],
            '4277' => [
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
            ],
            '4177' => [
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Perfectionist' => 3,],
                [ 'Specialist' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
                [ 'Objective Thinker' => 3,],
                [ 'Undershift' => 4,],
            ],
            '3777' => [
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                [ 'Counselor' => 4,],
                [ 'Practitioner' => 3,],
                
                [ 'Promoter' => 4,],
                
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
                [ 'Appraiser' => 3,],
                [ 'Promoter' => 4,],
            ]
        ];


        foreach ($sets as $mainkey => $set) {

            $datkeys = [];
            $datvals = [];
            $tempresult = [];

            $start = substr($mainkey, 0, 2);

            for ($l = 7; $l > 0; $l--) {
                for ($r = 7; $r > 0; $r--) {
                    $datkeys[] = sprintf("%d%d%d", $start, $l, $r);
                }
            }

            $sum = 0;
            $index = 0;

            while ($index < count($set)) {
                $pos = $set[$index];
                $key = array_keys($pos)[0];
                $val = $pos[$key];

                if ($val == 0) {
                    $index++;
                    continue;
                }

                $datvals[] = $key;

                $sum++;
                $set[$index][$key] --;
            }

            for ($i = 0; $i < count($datkeys); $i++) {
                $tempresult[$datkeys[$i]] = $datvals[$i];
            }

            $result[$mainkey] = $tempresult;
        }

        foreach ($result as $key => $values) {
            $positions[$key] = count($values);
            foreach ($values as $k => $v) {
                @$patterns[$v] ++;
            }
        }

        ksort($patterns);

        $out = [];
        $out[] = $result;
        $out[] = $positions;
        $out[] = $patterns;

        $data = json_encode($out, JSON_PRETTY_PRINT);
        $output->writeln($data);
    }

}
