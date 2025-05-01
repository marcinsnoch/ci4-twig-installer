<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index(): void
    {
        $this->twig->display('product/index');
    }

    public function new(): void
    {
        $this->twig->display('product/new');
    }

    public function create(): mixed
    {
        if ($this->validate('product')) {
            $data = $this->request->getPost(['name', 'price', 'description']);
            ProductModel::create($data);
            alertSuccess(lang('Product.alert.product_created'));

            return redirect()->back();
        }

        return redirect()->back()->withInput();
    }

    public function show(int $id): void
    {
        $product = ProductModel::find($id);

        $this->twig->display('product/show', compact('product'));
    }

    public function edit(int $id): void
    {
        $product = ProductModel::find($id);

        $this->twig->display('product/edit', compact('product'));
    }

    public function update(int $id): mixed
    {
        if ($this->validate('product')) {
            $product = ProductModel::find($id);
            $product->name = $this->request->getPost('name');
            $product->price = $this->request->getPost('price');
            $product->description = $this->request->getPost('description');
            $product->save();
            alertSuccess(lang('Product.alert.product_updated'));

            return redirect()->back();
        }

        return redirect()->back()->withInput();
    }

    public function delete(int $id): mixed
    {
        ProductModel::findOrFail($id)->delete();
        alertSuccess(lang('Product.alert.product_deleted'));

        return redirect()->to('products');
    }

    public function tableData(): mixed
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $products = ProductModel::all();

        return $this->response->setJSON($products);
    }
}
