<?php

class Node {
    public int $value;
    public ?Node $left;
    public ?Node $right;
    public int $height;

    public function __construct(int $value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
        $this->height = 1;
    }
}

class BalancedBST {
    private ?Node $root = null;

    public function insert(array $data): void {
        foreach ($data as $value) {
            $this->root = $this->insertNode($this->root, $value);
        }
    }

    private function insertNode(?Node $node, int $value): Node {
        if ($node === null) {
            return new Node($value);
        }

        if ($value < $node->value) {
            $node->left = $this->insertNode($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->insertNode($node->right, $value);
        }

        $node->height = 1 + max($this->getHeight($node->left), $this->getHeight($node->right));

        return $this->balance($node);
    }

    public function find(int $value): ?Node {
        $current = $this->root;

        while ($current !== null) {
            if ($value < $current->value) {
                $current = $current->left;
            } elseif ($value > $current->value) {
                $current = $current->right;
            } else {
                return $current;
            }
        }

        return null;
    }

    public function delete(int $value): void {
        $this->root = $this->deleteNode($this->root, $value);
    }

    private function deleteNode(?Node $node, int $value): ?Node {
        if ($node === null) {
            return null;
        }

        if ($value < $node->value) {
            $node->left = $this->deleteNode($node->left, $value);
        } elseif ($value > $node->value) {
            $node->right = $this->deleteNode($node->right, $value);
        } else {
            if ($node->left === null || $node->right === null) {
                return $node->left ?: $node->right;
            }

            $minNode = $this->findMin($node->right);
            $node->value = $minNode->value;
            $node->right = $this->deleteNode($node->right, $minNode->value);
        }

        $node->height = 1 + max($this->getHeight($node->left), $this->getHeight($node->right));

        return $this->balance($node);
    }

    private function findMin(Node $node): Node {
        while ($node->left !== null) {
            $node = $node->left;
        }

        return $node;
    }

    private function getHeight(?Node $node): int {
        return $node?->height ?? 0;
    }

    private function balance(Node $node): Node {
        $balanceFactor = $this->getHeight($node->left) - $this->getHeight($node->right);

        if ($balanceFactor > 1) {
            if ($this->getHeight($node->left->left) >= $this->getHeight($node->left->right)) {
                return $this->rotateRight($node);
            } else {
                $node->left = $this->rotateLeft($node->left);
                return $this->rotateRight($node);
            }
        }

        if ($balanceFactor < -1) {
            if ($this->getHeight($node->right->right) >= $this->getHeight($node->right->left)) {
                return $this->rotateLeft($node);
            } else {
                $node->right = $this->rotateRight($node->right);
                return $this->rotateLeft($node);
            }
        }

        return $node;
    }

    private function rotateRight(Node $y): Node {
        $x = $y->left;
        $T2 = $x->right;

        $x->right = $y;
        $y->left = $T2;

        $y->height = 1 + max($this->getHeight($y->left), $this->getHeight($y->right));
        $x->height = 1 + max($this->getHeight($x->left), $this->getHeight($x->right));

        return $x;
    }

    private function rotateLeft(Node $x): Node {
        $y = $x->right;
        $T2 = $y->left;

        $y->left = $x;
        $x->right = $T2;

        $x->height = 1 + max($this->getHeight($x->left), $this->getHeight($x->right));
        $y->height = 1 + max($this->getHeight($y->left), $this->getHeight($y->right));

        return $y;
    }
}