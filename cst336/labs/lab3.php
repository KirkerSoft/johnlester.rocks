<HTML>
<HEAD>
	<TITLE>CST336 Lab 3 by John Lester</TITLE>
</HEAD>
<BODY>
	<center><table width="600"><tr><td align="center"><form action="lab3.php" method="post">
		<fieldset>
			<legend>Caesar Cipher</legend>
		<table border=0>
			<tr><td align="right">Shift:</td><td><input type="text" name="shift" size="5" <?php if (!empty($_POST['shift'])) { echo "value=".$_POST['shift']; } ?>> (use inverse to decode)</td></tr>
			<tr><td valign="top" align="right">Text:</td><td><textarea name="text" cols="50" rows="5"><?php if (!empty($_POST['text'])) { echo strtolower($_POST['text']); } ?></textarea></td></tr>
			<tr><td colspan="2" align="center"><input type="submit"> <input type="reset"></td></tr>
			<?php
			if (!empty($_POST['text'])) {
				$strText = strtolower($_POST['text']);
				echo "<tr><td></td><td><textarea name=result cols=50 rows=5 readonly>";
				for ($i=0; $i<=strlen($strText)-1; $i++) { // iterate through each letter of text except last
					$ascii = ord(substr($strText, $i, 1)); // borrowed from http://stackoverflow.com/questions/8077221/php-addition-subtraction-of-letters
					if (substr($strText, $i, 1) == ' ') {
						echo ' '; // leave spaces alone
					}
					elseif ($ascii<97 || $ascii>122) {
						echo '-'; // leave characters outside alphabet alone (or convert to -)
					}
					else {
						$ascii = (((($ascii -97) +$_POST['shift']) %26) +97);
						if ($ascii < 97) {
							$ascii += 26;
						}
						echo chr($ascii);
					}
				}
				echo "</textarea></td></tr>";
			}
			?>
		</table>
		</fieldset>
	</form></td></tr>
	<tr><td colspan="2">
		In cryptography, a Caesar cipher, also known as Caesar's cipher, the shift cipher, Caesar's code or Caesar shift, is one of the simplest and most widely known encryption techniques. It is a type of substitution cipher in which each letter in the plaintext is replaced by a letter some fixed number of positions down the alphabet. For example, with a left shift of 3, D would be replaced by A, E would become B, and so on. The method is named after Julius Caesar, who used it in his private correspondence.<br>
		The encryption step performed by a Caesar cipher is often incorporated as part of more complex schemes, such as the Vigenère cipher, and still has modern application in the ROT13 system. As with all single-alphabet substitution ciphers, the Caesar cipher is easily broken and in modern practice offers essentially no communication security.
	</td></tr>
	<tr><td align="right">
		Courtesy of <a href="https://en.wikipedia.org/wiki/Caesar_cipher" target="_blank">Wikipedia</a>
	</td></tr></table></center>
</BODY>
</HTML>
