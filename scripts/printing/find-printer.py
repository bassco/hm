
import subprocess

printers={"2305":"Slip","202a":"Label"}

def getPrinter(dev):
  res = None
  #  dmesg|grep lp|grep "2305$"|tail -n1|cut -f3 -d":"|tr -d ' '
  dmesg = subprocess.Popen(['dmesg'], 
                        stdout=subprocess.PIPE,
                        )

  greplp = subprocess.Popen(['grep', 'lp'],
                        stdin=dmesg.stdout,
                        stdout=subprocess.PIPE,
                        )

  grepdev = subprocess.Popen(['grep', '-i', '%s$' % dev ],
                        stdin=greplp.stdout,
                        stdout=subprocess.PIPE,
                        )

  tail = subprocess.Popen(['tail', '-n', '1'],
                        stdin=grepdev.stdout,
                        stdout=subprocess.PIPE,
                        )

  cut = subprocess.Popen(['cut', '-f', '3', '-d:'],
                        stdin=tail.stdout,
                        stdout=subprocess.PIPE,
                        )

  tr = subprocess.Popen(['tr', '-d', ' '],
                        stdin=cut.stdout,
                        stdout=subprocess.PIPE,
                        )

  result = tr.stdout
  for line in result:
    res = line.strip()
  return res

for dev in printers:
  print printers[dev], getPrinter(dev)
