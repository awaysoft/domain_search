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
                $str  = '0123456789abcdefghijklmnopqrstuvwxyz';
                $str  = '0123456789';
                $len  = 2;
                $exp  = '.com';
                $pre  = '';
                $last = 'host';
		$filename = 'output_host2.txt';
		if ($argc > 1) {
			$filename = $argv[1];
		}
		$fd = fopen($filename, 'w+');

                $arr  = array();
		/* 初始化$arr */
		for ($i = 0; $i < $len; ++$i) {
			array_push($arr, 0);
		}
                $quit = false;

                $exists_domains = array();
                do {
                                $domain = $pre;
                                for ($i = 0; $i < count($arr); ++$i) {
                                                $domain .= $str[$arr[$i]];
                                }
                                $domain .= $last . $exp;
                                echo "正在查找{$domain}：";
                                $result = exec_command("whois -n {$domain}");
                                if (strpos($result, 'No match for domain') !== false) {
                                                echo '未注册';
						fwrite($fd, $domain . "\n");
                                                array_push($exists_domains, $domain);
                                } else {
                                                echo '已注册';
                                }
                                echo "\n";

                                $arr[count($arr) - 1] ++;
                                for ($i = count($arr) - 1; $i >= 0; --$i) {
                                                if ($arr[$i] >= strlen($str)) {
                                                                if ($i == 0) {
                                                                                $quit = true;
                                                                } else {
                                                                                $arr[$i - 1] ++;
                                                                                $arr[$i] = 0;
                                                                }
                                                }
                                }
                }while(!$quit);

                echo "未注册列表：\n";
                echo implode("\n", $exists_domains);
		fclose($fd);
