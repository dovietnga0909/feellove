import MySQLdb

try:
    db = MySQLdb.connect('localhost','root', 'TungL@m20!6', 'feedlove')
    cursor = db.cursor()        
    cursor.execute("SELECT VERSION()")
    results = cursor.fetchone()
    # Check if anything at all is returned
    if results:
        print 'True'
    else:
        print 'False'              
except MySQLdb.Error:
    print "ERROR IN CONNECTION"

