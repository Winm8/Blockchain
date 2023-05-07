<?php

class Chain implements IChain
{
    private array $blocks;

    public function __construct()
    {
        $this->blocks = [];
    }

    public function addBlock(Block $block): static
    {
        $block->id = count($this->blocks) + 1;
        $block->dttm = date('Y-m-d H:i:s');
        $block->hash = $this->calculateHash($block);
        
        if (!empty($this->blocks)) {
            $previousBlock = $this->getLastBlock();
            $block->hash .= $previousBlock->getHash();
        }
        
        $this->blocks[] = $block;
        return $this;
    }

    public function getBlock(int $id): ?Block
    {
        return $this->blocks[$id - 1] ?? null;
    }

    public function getLastBlock(): ?Block
    {
        return end($this->blocks) ?: null;
    }

    public function isValid(): bool
    {
        foreach ($this->blocks as $i => $block) {
            if ($i > 0) {
                $previousBlock = $this->blocks[$i - 1];
                $expectedHash = $this->calculateHash($block);
                $expectedHash .= $previousBlock->getHash();

                if ($block->getHash() !== $expectedHash) {
                    return false;
                }
            }
        }

        return true;
    }

    private function calculateHash(Block $block): string
    {
        return hash('sha256', $block->getContent());
    }
}