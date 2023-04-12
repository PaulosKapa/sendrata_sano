import RPi.GPIO as GPIO
from mfrc522 import SimpleMFRC522

reader = SimpleMFRC522()

while True:
	id, text = reader.read()
	print(id)
	if id!=0:
		file1 = open("file.json", "w")
		file1.write('{"id":'+str(id)+'}')
		file1.close()
