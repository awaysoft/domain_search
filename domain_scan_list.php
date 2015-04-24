<?php
                function exec_command($command) {
                                $output = null;
                                $result = exec($command, $output);
                                if (!$result) {
                                                return false;
                                } else {
                                                return implode("\n", $output);
                                }
                }
		$file_content = file_get_contents('py2.dic');
                $arr  = explode("\n", $file_content);
		
		$index = 0;
		$count = count($arr);
                $exists_domains = array();
		$filename = 'output.lst';
		if ($argc > 1) {
			$filename = $argv[1];
		}
		$fd = fopen($filename, 'w+');
                do {
                                $domain = $arr[$index] . '.com';
                                echo "{$index}:正在查找{$domain}：";
                                $result = exec_command("whois -n {$domain}");
                                if (strpos($result, 'No match for domain') !== false) {
                                                echo '未注册';
						fwrite($fd, $domain . "\n");
                                                array_push($exists_domains, $domain);
                                } else {
                                                echo '已注册';
                                }
                                echo "\n";
				$index ++;
                }while($index < $count);

                echo "未注册列表：\n";
                echo implode("\n", $exists_domains);
		fclose($fd);
