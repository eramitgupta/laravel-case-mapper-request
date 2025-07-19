# Laravel Case Mapper Request

A Laravel package that transforms request input keys using PHP 8+ attributes. Supports formats like camelCase, snake_case, StudlyCase, UPPERCASE, enabling flexible data handling from varied sources. Ideal for backend services needing consistent input structures, regardless of client naming conventions.
> 📦 Designed for Laravel 10, 11, and 12 — No core modification required.

---

## ✨ Features

* 🧠 Automatically map incoming request keys to match your validation expectations
* 🧩 Attribute-based syntax using `#[MapName(...)]`
* 🔄 Supports multiple naming conventions
* 🧩 Integrates seamlessly with Laravel `FormRequest`
* 📚 Extendable with custom mappers
* ✅ Zero configuration, zero core **hacks**
* 🎨 Works with any frontend (React, Vue, etc.)

> 💡 Recommended: Vue or React for best developer experience

---

## 🔧 Supported Case Mappers

| Mapper Class       | Frontend Input | Validated As output |
|--------------------|----------------|---------------------|
| `SnakeCaseMapper`  | `firstName`    | `first_name`        |
| `CamelCaseMapper`  | `first_name`   | `firstName`         |
| `StudlyCaseMapper` | `FirstName`    | `first_name`        |
| `UpperCaseMapper`  | `FIRSTNAME`    | `firstname`         |

---

## 📦 Installation

```bash
composer require erag/laravel-case-mapper-request
```

> No config file or publishing required.

---
##  Create a Form Request with Case Mapping

### 🧩 1. Create a Form Request class

```bash
php artisan make:request ContactRequest
```

---

### 🧩 2. Add the attribute and trait

```php
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Traits\HasMapNameTransformers;
use LaravelCaseMapperRequest\Mappers\CamelCaseMapper;

#[MapName(CamelCaseMapper::class)] // or SnakeCaseMapper, UpperCaseMapper, etc.
class ContactRequest extends FormRequest
{
    use HasMapNameTransformers;

    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email_address' => 'required|email',
        ];
    }

   
}
```

---

### 🧩 3. Use it in a Controller

```php
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        // Example:
        // Contact::create($validated);

        return response()->json([
            'message' => 'Submitted successfully!',
            'data' => $validated
        ]);
    }
}
```

---

### Step 3: Frontend Input Example (Vue + Inertia)

```vue
<template>
  <form @submit.prevent="submit">
    <input v-model="form.firstName" placeholder="First Name" />
    <input v-model="form.lastName" placeholder="Last Name" />
    <input v-model="form.emailAddress" placeholder="Email" />
    <button type="submit">Submit</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  firstName: '',
  lastName: '',
  emailAddress: ''
})

function submit() {
  form.post('/form/snake')
}
</script>
```

### Step 4: Backend Automatically Maps It

**Incoming :**

```json
{
  "firstName": "Amit",
  "lastName": "Gupta",
  "emailAddress": "amit@example.com"
}
```

**Mapped for Validation output:**

```php
[
  'first_name' => 'Amit',
  'last_name' => 'Gupta',
  'email_address' => 'amit@example.com'
]
```

---

## 🧠 Defining Your Own Case Mapper

Need to support a custom casing style or transformation logic?  
You can easily create your own mapper by implementing the `CaseMapperContract`

Just implement the contract:

```php
use LaravelCaseMapperRequest\Contracts\CaseMapperContract;

class KebabCaseMapper implements CaseMapperContract
{
    public static function map(array $data): array
    {
        return collect($data)
            ->mapWithKeys(fn($value, $key) => [Str::kebab($key) => $value])
            ->toArray();
    }
}
```

Then apply to any FormRequest:

```php
#[MapName(KebabCaseMapper::class)]
```

---

## 📄 License

MIT © [Amit Gupta](https://github.com/eramitgupta)

---

## 🔗 Connect

Made with ❤️ by **[Amit Gupta](https://github.com/eramitgupta)**

* 🐦 [Twitter](https://twitter.com/_eramitgupta)
* 💼 [LinkedIn](https://linkedin.com/in/eramitgupta)

---

> ⭐ Found this useful? Star it on GitHub!
