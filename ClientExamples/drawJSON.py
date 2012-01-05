#! /usr/bin/env python
 
import json
from networkx import *
import sys

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
colors=[]
for v in H['vertexes']:
	G.add_node(v, node_color='b')
#	colors.append(H['vertexes'][v]['color'])
	
for v in H['adjacency']:
	for u in H['adjacency'][v]:
		data = H['adjacency'][v][u]
		G.add_edge(v, u, data)

pos=nx.spring_layout(G) # positions for all nodes

# nodes
nx.draw_networkx_nodes(G,pos,node_size=200, node_color=colors)

# edges
nx.draw_networkx_edges(G,pos)

# labels
nx.draw_networkx_labels(G,pos,font_size=10,font_family='sans-serif')

plt.axis('off')
plt.savefig("weighted_graph.png") # save as png
plt.show() # display

