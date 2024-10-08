<?php declare(strict_types=1);

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Lib\Graph;

/**
 * Atomic graph entity, Node
 *
 * A graph is made up of nodes (aka. nodes, or points) which are connected by
 * edges (aka arcs, or lines) therefore node is the fundamental unit of
 * which graphs are formed.
 *
 * Nodes are indivisible, yet they share some common characteristics with edges.
 * In Pho context, these commonalities are represented with the EntityInterface.
 *
 * Uses Observer Pattern to observe updates from its attribute bags.
 *
 * Last but not least, this class is declared \Serializable. While it does nothing
 * special within this class, this declaration may be useful for subclasses to override
 * and persist data.
 *
 * @see EdgeList
 *
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Node implements
    EntityInterface,
    EntityWorkerInterface,
    NodeInterface,
    NodeWorkerInterface,
    HookableInterface,
    Event\EmitterInterface
{

    use SerializableTrait;
    use EntityTrait {
        EntityTrait::__construct as ____construct;
    }
    use HookableTrait;
    use Event\EmitterTrait;

    /**
     * Internal variable that keeps track of edges in and out.
     *
     * @var EdgeList
     */
    protected $edge_list;

    /**
     * The graph context of this node
     *
     * @var GraphInterface
     */
    protected $context;

    /**
     * The ID of the graph context of this node
     *
     * @var string
     */
    protected $context_id;

    /**
     * {@inheritdoc}
     */
    public function __construct(GraphInterface $context)
    {
        $this->____construct();
        $this->edge_list = new EdgeList($this);
        $context->add($this)->context = $context;
        $this->context_id = (string) $context->id();
        //$this->attachGraphObservers($context);
        $this->init();
        Logger::info("A node with id \"%s\" and label \"%s\" constructed", $this->id(), $this->label());
    }

    /**
     * Adds the context itself and the context's contexts (if available)
     * recursively to the list of observers for deletion.
     *
     * @param GraphInterface $context
     *
     * @return void
     */
    /*private function attachGraphObservers(GraphInterface $context): void
    {
        while($context instanceof SubGraph) {
            $this->attach($context);
            $context = $context->context();
        }
        $this->attach($context);
    }
    */

    /**
     * {@inheritdoc}
     */
    public function context(): GraphInterface
    {
        if(isset($this->context)) {
            return $this->context;
        }
        return $this->hookable();
    }

    /**
     * {@inheritdoc}
     */
    public function changeContext(GraphInterface $context): void
    {
        $this->context()->remove($this->id());
        $this->context = $context;
        $this->context_id = $context->id();
        $this->context->add($this);
        $this->emit("modified");
    }

    /**
     * {@inheritdoc}
     */
    public function edges(): EdgeList
    {
        return $this->edge_list;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $array = $this->entityToArray();
        $array["edge_list"] = $this->edge_list->toArray();
        $array["context"] = (string) $this->context_id;
        return $array;
    }

    /**
     * {@inheritDoc}
     */
    public function edge(string $id): EdgeInterface
    {
        return $this->hookable();
    }

}
