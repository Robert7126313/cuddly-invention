const { useState, useCallback } = React;
const { ReactFlow: ReactFlowComponent, addEdge, MiniMap, Controls, Background, applyNodeChanges, applyEdgeChanges } = window.ReactFlow;

const initialNodes = [
  { id: '1', position: { x: 0, y: 0 }, data: { label: 'First note' } }
];
const initialEdges = [];

function App() {
  const [nodes, setNodes] = useState(initialNodes);
  const [edges, setEdges] = useState(initialEdges);

  const onConnect = useCallback((params) => setEdges((eds) => addEdge(params, eds)), []);
  const onNodesChange = useCallback((changes) => setNodes((nds) => applyNodeChanges(changes, nds)), []);
  const onEdgesChange = useCallback((changes) => setEdges((eds) => applyEdgeChanges(changes, eds)), []);

  return React.createElement(
    ReactFlowComponent,
    { nodes, edges, onConnect, onNodesChange, onEdgesChange, fitView: true },
    React.createElement(MiniMap, null),
    React.createElement(Controls, null),
    React.createElement(Background, { gap: 16 })
  );
}

ReactDOM.render(React.createElement(App), document.getElementById('root'));
