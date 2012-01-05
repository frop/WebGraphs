#! /usr/bin/env python
 
import json
from networkx import *
import sys
url="http://www-personal.umich.edu/~mejn/netdata/football.zip"

try: # Python 3.x
    import urllib.request as urllib
except ImportError: # Python 2.x
    import urllib

try:
    import matplotlib.pyplot as plt
except:
    raise
    
import urllib2
import simplejson

req = urllib2.Request("http://localhost/graph/"+sys.argv[1]+".json")
opener = urllib2.build_opener()
f = opener.open(req)

H = simplejson.load(f)

G=nx.Graph()

for v in H['vertexes']:
	G.add_node(v)
	
for v in H['adjacency']:
	for u in H['adjacency'][v]:
		data = H['adjacency'][v][u]
		G.add_edge(v, u, data)

elarge=[(u,v) for (u,v,d) in G.edges(data=True) if d['weight'] >0.5]
esmall=[(u,v) for (u,v,d) in G.edges(data=True) if d['weight'] <=0.5]

pos=nx.spring_layout(G) # positions for all nodes

# nodes
nx.draw_networkx_nodes(G,pos,node_size=200)

# edges
nx.draw_networkx_edges(G,pos)

# labels
nx.draw_networkx_labels(G,pos,font_size=10,font_family='sans-serif')

plt.axis('off')
plt.savefig("weighted_graph.png") # save as png
plt.show() # display

