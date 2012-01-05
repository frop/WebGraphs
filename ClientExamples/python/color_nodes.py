#! /usr/bin/env python

import matplotlib.pyplot as plt

import sys
from lib import *

#> python color_nodes.py 000000

G = json_to_nx(load_json_graph(sys.argv[1]))

pos=nx.spring_layout(G) # positions for all nodes

colors = []

for v in G.nodes(data=True):
	colors.append(v[1]['color'])

# nodes
nx.draw_networkx_nodes(G,pos,node_size=200, node_color=colors)

# edges
nx.draw_networkx_edges(G,pos)

# labels
nx.draw_networkx_labels(G,pos,font_size=10,font_family='sans-serif')

plt.axis('off')
#plt.savefig("weighted_graph.png") # save as png
plt.show() # display

