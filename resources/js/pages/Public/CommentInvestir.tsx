import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '@/layouts/public-layout';
import { HowItWorksPage } from '@/screens/visitor';

export default function Page() {
  return (
    <>
      <Head title="CommentInvestir" />
      <HowItWorksPage />
    </>
  );
}

Page.layout = (page: ReactNode) => <PublicLayout>{page}</PublicLayout>;
