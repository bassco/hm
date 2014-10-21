#!/usr/bin/python
# -*- coding: utf-8 -*-

__version__ = "0.1"
__author__ = "Andrew Basson"
"""Read an order from the DB and print a picking list

Author: %s
Version: %s

2014-10-03 0.0 Initial Version
""" % (__author__, __version__)

import pymysql
from io import BytesIO

from kajiki import FileLoader
import os
import sys
from optparse import OptionParser
from datetime import date

db_to = pymysql.connect (host = "sql8.cpt3.host-h.net",
                          user = "hobbybwyan_2",
                          passwd = "sX8YrMd8",
                          db = "hobbycart")

STORE_ID=0
LANG_ID=1

FOOTER=BytesIO(b"""xCLfmt6\r
zC\r
""")

def escape_html(data):
        return pymysql.escape_string(data)

def writeToPrinter(labels, fn):
#  Template = loader.import_(template)
#  t=Template(data)
  fr=open("templates/shipping.bin","rb")
  with open("/tmp/%s" % fn,"wb") as fh:
    fh.write(fr.read())
    for lab in labels:
      try:
        fh.write(lab.label())
      except AttributeError as e:
        sys.stderr.write("%s\n" % (e.message)) 
        pass 
    fh.write(FOOTER.getvalue())
  fr.close() 
class Label():

    def __init__(self, order_id=None):
        self._telephone=""
        self._firstname=""
        self._lastname=""
        self._company=""
        self._address_1=""
        self._address_2=""
        self._city=""
        self._zone=""
        self._postcode=""
        self.order_id=order_id
        if self.order_id is not None: self.__populateFromDB()

    @property
    def telephone(self):
        t = self._telephone.replace(" ","").replace("+","").replace("-","").strip()
        if len(t) > 10:
          if t.startswith("27"): t = "0" + t[2:]
        return t

    @property
    def name(self):
        s = self._lastname.strip().split(" ")
        s = [ n.lower() for n in s ]
        s[-1] = s[-1].capitalize()
        res = " ".join([self._firstname.strip().capitalize(), " ".join(s)])


    @property
    def company(self):
        s = self._company.strip().upper()
        if len(s) > 30: raise AttributeError("Order %s: Company too long." % self.order_id)
        return s

    @property
    def address_1(self):
        s =  self._address_1.strip().title()
        if len(s) > 30: raise AttributeError("Order %s: Address 1 too long." % self.order_id)
        return s

    @property
    def address_2(self):
        s =  self._address_2.strip().title()
        if len(s) > 30: raise AttributeError("Order %s: Address 2 too long." % self.order_id)
        return s

    @property
    def city(self):
        s =  self._city.strip().title()
        if len(s) > 30: raise AttributeError("Order %s: City too long." % self.order_id)
        return s

    @property
    def zone(self):
        return self._zone.strip().upper()

    @property
    def postcode(self):
        if self._postcode is None or self._postcode =="": raise AttributeError("Order %s: Need a post code" % self.order_id)
        return self._postcode.strip()


    def __populateFromDB(self):
        c = db_to.cursor()
        c.execute("select telephone, shipping_firstname, shipping_lastname, shipping_company, shipping_address_1,shipping_address_2,shipping_city,shipping_zone,shipping_postcode from `order` where order_id=%s" % self.order_id) 
        data = c.fetchone()
        self._telephone = data[0]
        self._firstname=data[1]
        self._lastname=data[2]
        self._company=data[3]
        self._address_1=data[4]
        self._address_2=data[5]
        self._city=data[6]
        self._zone=data[7]
        self._postcode=data[8]
        print data
        c.close()


    def label(self):
        return """L\r
D11\r
rfmt6\r
A2\r
1e6301600280071BORD&D%(order_id)s\r
ySPM\r
1911A0600170128ORD%(order_id)s\r
1911C1001700138Attn: %(name)s\r
1911C1001550138%(company)s\r
1911C1001400138%(address1)s\r
1911C1001250138%(address2)s\r
1911C1001100138%(city)s\r
1911C1000950138%(zone)s\r
1911C1000800138%(postcode)s\r
1911C1000560138PH:%(telephone)s\r
Q0001\r
E\r
""" % ({'order_id':self.order_id, 
  'telephone':self.telephone,
  'name':self.name,
  'company':self.company,
  'address1':self.address_1,
  'address2':self.address_2,
  'city':self.city, 
  'zone':self.zone,
  'postcode':self.postcode })

def processShippingLabels(orders, printer):
    labels=[]
    for order in orders:
      l = Label(order)
      labels.append(l)
      #print l
    print printer
    writeToPrinter(labels, printer)

if __name__ == "__main__":
    DESCRIPTION="""Create labels for the CL-S521 for Hobbymania"""
    EPILOG="""Copyright: Bassco IT Consulting 2014\tAuthor: %s""" % __author__
    try:
        parser = OptionParser("usage: %prog [args] [options]", version="%prog " + __version__, \
            description=DESCRIPTION, epilog=EPILOG)
    except TypeError:
        parser = OptionParser("usage: %prog [args] [options]", version="%prog " + __version__, \
            description=DESCRIPTION)

    parser.add_option("-p", "--printer-dev", dest="lp", type="string",
                    action="store",
                    help="Printer device e.g. lp0")
    # Parse the command-line
    (options, args) = parser.parse_args()
    

    # Set some sane args if options not set
    if len(args) >= 1:
        options.labels=args
    else:
       sys.exit("Need order ids to print")

    processShippingLabels(options.labels, options.lp)
