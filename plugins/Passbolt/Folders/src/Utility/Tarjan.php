<?php
declare(strict_types=1);

/**
 * Tarjan strongly connected components detection utility.
 * The utility is based on the code of Tomáš Fülöpp, licensed under GPL v2, see:
 * http://www.vacilando.org/article/php-implementation-tarjans-cycle-detection-algorithm
 * https://github.com/Vacilando/php-tarjan
 */

namespace Passbolt\Folders\Utility;

class Tarjan
{
    /**
     * Detect strongly connected components in an adjacency graph.
     *
     * @param array $graph The graph to look into
     * [
     *   0 => [2]
     *   1 => [2]
     *   2 => [3, 4]
     *   3 => [5]
     *   4 => [0]
     *   5 => [0]
     * ]
     * @return array list of SCCs found in the graph
     * [
     *    '0|2|3|5',
     *    '1|2|4',
     * ]
     */
    public static function detect(array $graph)
    {
        $graphSize = count($graph);
        $cycles = [];
        $marked = array_fill(0, $graphSize, false);
        $markedStack = [];
        $pointStack = [];

        // Loop on all nodes of the graph to ensure that nodes that are not reachable from the first node are still
        // eventually traversed.
        for ($i = 0; $i < $graphSize; $i++) {
            self::detectOnNode($i, $i, $graph, $cycles, $marked, $markedStack, $pointStack);
            while (!empty($markedStack)) {
                $marked[array_pop($markedStack)] = false;
            }
        }

        return array_keys($cycles);
    }

    /**
     * Apply the tarjan algorithm on a node
     *
     * @param int $initialNode The initial node the algorithm has explored
     * @param int $node The current node the algorithm is exploring
     * @param array $graph The adjacency graph
     * @param array $cycles The discovered cycles
     * @param array $marked The list of nodes status
     * @param array $markedStack The list of nodes explored
     * @param array $pointStack The current list of connected nodes explored
     * @return bool
     */
    private static function detectOnNode($initialNode, $node, &$graph, &$cycles, &$marked, &$markedStack, &$pointStack)
    {
        $found = false;
        $pointStack[] = $node;
        $marked[$node] = true;
        $markedStack[] = $node;

        // Consider successors of the node
        foreach ($graph[$node] as $successorNode) {
            // @t0d0-tm Optimization? Is it cutting a branch that has already been treated?
            if ($successorNode < $initialNode) {
                $graph[$successorNode] = [];
            } elseif ($successorNode == $initialNode) {
                $cycles[implode('|', $pointStack)] = true;
                $found = true;
            } elseif ($marked[$successorNode] === false) {
                $foundOnSuccessor = self::detectOnNode(
                    $initialNode,
                    $successorNode,
                    $graph,
                    $cycles,
                    $marked,
                    $markedStack,
                    $pointStack
                );
                if ($found || $foundOnSuccessor) {
                    $found = true;
                }
            }
        }

        if ($found) {
            while (end($markedStack) != $node) {
                $marked[array_pop($markedStack)] = false;
            }
            array_pop($markedStack);
            $marked[$node] = false;
        }

        array_pop($pointStack);

        return $found;
    }
}
